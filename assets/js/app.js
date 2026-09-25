/**
 * Match — interacciones del tema.
 * Vanilla con mejora progresiva. Dependencias (assets/vendor): AOS para las
 * animaciones de entrada, Embla para los carruseles y Lenis para el scroll
 * suave.
 */
( function () {
	'use strict';

	var reducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* --- Scroll suave (Lenis) ---
	 * Usa el scroll nativo (no transform), así AOS, IntersectionObserver y los
	 * anclas siguen funcionando. Se omite con prefers-reduced-motion.
	 */
	var lenis = null;

	if ( window.Lenis && ! reducedMotion ) {
		lenis = new window.Lenis( { autoRaf: true, lerp: 0.1 } );

		/* Anclas a mano en vez de la opción `anchors` de Lenis: esa mide con
		 * getBoundingClientRect, que incluye el translateY(100px) que AOS pone a
		 * las secciones aún no animadas, y el destino queda 100 px pasado.
		 * offsetTop ignora los transforms. El destino puede pedir más aire por
		 * arriba (el navbar es sticky) con scroll-margin-top en CSS, como
		 * .match-legal__body h2; sin eso se usan los 16 px de siempre.
		 */
		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest( 'a[href*="#"]' );
			if ( ! link || link.origin !== window.location.origin || link.pathname !== window.location.pathname ) { return; }
			var target = link.hash.length > 1 ? document.getElementById( decodeURIComponent( link.hash.slice( 1 ) ) ) : null;
			if ( ! target ) { return; }
			event.preventDefault();
			var y = 0;
			for ( var el = target; el; el = el.offsetParent ) { y += el.offsetTop; }
			var margin = parseFloat( getComputedStyle( target ).scrollMarginTop ) || 16;
			lenis.scrollTo( Math.max( 0, y - margin ) );
			if ( window.history.pushState ) { window.history.pushState( null, '', link.hash ); }
		} );
	}

	/* --- Menú móvil --- */
	var toggle = document.querySelector( '.match-navbar__toggle' );
	var panel = document.getElementById( 'match-mobile-nav' );

	if ( toggle && panel ) {
		toggle.addEventListener( 'click', function () {
			var open = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
			panel.hidden = open;
			document.body.classList.toggle( 'has-open-nav', ! open );
			if ( lenis ) { open ? lenis.start() : lenis.stop(); }
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && toggle.getAttribute( 'aria-expanded' ) === 'true' ) {
				toggle.click();
				toggle.focus();
			}
		} );
	}

	/* --- Acordeón de soluciones --- */
	var panels = Array.prototype.slice.call( document.querySelectorAll( '.match-accordion__panel' ) );

	panels.forEach( function ( item ) {
		var trigger = item.querySelector( '.match-accordion__trigger' );

		if ( ! trigger ) {
			return;
		}

		trigger.addEventListener( 'click', function () {
			panels.forEach( function ( other ) {
				var otherTrigger = other.querySelector( '.match-accordion__trigger' );
				var isTarget = other === item;

				other.classList.toggle( 'is-open', isTarget );
				if ( otherTrigger ) {
					otherTrigger.setAttribute( 'aria-expanded', isTarget ? 'true' : 'false' );
				}
			} );
		} );
	} );

	/* --- Filas de vacantes: expandir / contraer ---
	 * Delegado en document porque las filas se reemplazan al filtrar.
	 */
	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '.match-job__toggle' );
		if ( ! toggle ) { return; }
		var row = toggle.closest( '.match-job' );
		var open = row.classList.toggle( 'is-open' );
		toggle.classList.toggle( 'is-active', open );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	} );

	/* --- Contacto: selector de perfil --- */
	document.querySelectorAll( '.match-toggle__group' ).forEach( function ( group ) {
		var input = group.parentNode.querySelector( 'input[name="perfil"]' );
		group.querySelectorAll( '.match-toggle__opt' ).forEach( function ( opt ) {
			opt.addEventListener( 'click', function () {
				group.querySelectorAll( '.match-toggle__opt' ).forEach( function ( other ) {
					var active = other === opt;
					other.classList.toggle( 'is-active', active );
					other.setAttribute( 'aria-checked', active ? 'true' : 'false' );
				} );
				if ( input ) {
					input.value = opt.dataset.value;
				}
			} );
		} );
	} );

	/* --- Filtros de vacantes en la portada ---
	 * Cada cambio consulta match/v1/jobs (inc/jobs-filters.php) y reemplaza
	 * filas, conteo, enlace "Ver todas" y las opciones de los otros selects
	 * (conteos facetados). La URL se actualiza para poder compartirla. Si
	 * fetch falla, se envía el formulario al archivo como sin JavaScript.
	 */
	document.querySelectorAll( '[data-jobs-form]' ).forEach( function ( form ) {
		var endpoint = form.dataset.endpoint;
		var section = form.closest( '.match-vacantes' );
		var list = section && section.querySelector( '[data-jobs-list]' );

		if ( ! endpoint || ! list || ! window.fetch || ! window.AbortController ) { return; }

		var count = section.querySelector( '[data-jobs-count]' );
		var all = section.querySelector( '[data-jobs-all]' );
		var clear = form.querySelector( '[data-jobs-clear]' );
		var divider = form.querySelector( '[data-jobs-divider]' );
		var selects = Array.prototype.slice.call( form.querySelectorAll( 'select' ) );
		var controller = null;

		function syncPill( select ) {
			var pill = select.closest( '.match-filter' );
			var text = pill.querySelector( '[data-filter-label]' );
			var option = select.options[ select.selectedIndex ];
			pill.classList.toggle( 'is-active', !! select.value );
			text.textContent = select.value && option ? ( option.dataset.name || option.textContent ) : select.dataset.label;
		}

		function params() {
			var query = new URLSearchParams();
			selects.forEach( function ( select ) { if ( select.value ) { query.set( select.name, select.value ); } } );
			return query;
		}

		function rebuildOptions( facets ) {
			selects.forEach( function ( select ) {
				var options = facets && facets[ select.name ];
				if ( ! options ) { return; }
				var current = select.value;
				var currentName = current && select.options[ select.selectedIndex ] ? ( select.options[ select.selectedIndex ].dataset.name || current ) : '';
				select.length = 1;
				options.forEach( function ( item ) {
					var option = new Option( item.name + ' (' + item.count + ')', item.slug );
					option.dataset.name = item.name;
					select.add( option );
				} );
				select.value = current;
				if ( current && select.value !== current ) {
					// La opción elegida ya no combina con los demás filtros: se conserva con (0).
					var kept = new Option( currentName + ' (0)', current );
					kept.dataset.name = currentName;
					select.add( kept );
					select.value = current;
				}
				syncPill( select );
			} );
		}

		function load() {
			var query = params();
			var own = new AbortController();

			if ( controller ) { controller.abort(); }
			controller = own;

			list.classList.add( 'is-loading' );
			list.setAttribute( 'aria-busy', 'true' );
			// "Borrar filtros" solo existe cuando hay algo que borrar
			var none = '' === query.toString();
			if ( clear ) { clear.hidden = none; }
			if ( divider ) { divider.hidden = none; }

			fetch( endpoint + ( endpoint.indexOf( '?' ) === -1 ? '?' : '&' ) + query.toString(), { signal: own.signal, headers: { Accept: 'application/json' } } )
				.then( function ( response ) { return response.ok ? response.json() : Promise.reject( new Error( response.status ) ); } )
				.then( function ( data ) {
					list.innerHTML = data.html;
					list.querySelectorAll( '.match-job' ).forEach( function ( row, i ) {
						row.classList.add( 'is-entering' );
						row.style.animationDelay = ( i * 60 ) + 'ms';
					} );
					if ( count ) { count.innerHTML = data.count_html; }
					if ( all && data.archive_url ) { all.href = data.archive_url; }
					rebuildOptions( data.facets );

					var search = query.toString();
					window.history.replaceState( null, '', window.location.pathname + ( search ? '?' + search : '' ) + '#vacantes' );
				} )
				.catch( function ( error ) {
					if ( error && error.name === 'AbortError' ) { return; }
					form.submit();
				} )
				.then( function () {
					if ( controller === own ) {
						list.classList.remove( 'is-loading' );
						list.setAttribute( 'aria-busy', 'false' );
					}
				} );
		}

		selects.forEach( function ( select ) {
			select.onchange = null;
			select.removeAttribute( 'onchange' );
			select.addEventListener( 'change', function () { syncPill( select ); load(); } );
		} );

		form.addEventListener( 'submit', function ( event ) { event.preventDefault(); load(); } );
		form.addEventListener( 'reset', function ( event ) {
			event.preventDefault();
			selects.forEach( function ( select ) { select.value = ''; syncPill( select ); } );
			load();
		} );

		// "Borrar filtros" del estado vacío (llega con las filas del endpoint)
		section.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.match-vacantes__empty [data-jobs-clear]' ) ) { form.reset(); }
		} );
	} );

	/* --- Animaciones de entrada (AOS) ---
	 * Los elementos llevan data-aos="fade-up" (y data-aos-delay para escalonar).
	 * Con prefers-reduced-motion AOS se desactiva y todo se muestra de una.
	 */
	if ( window.AOS ) {
		window.AOS.init( {
			duration: 700,
			easing: 'ease-out-cubic',
			offset: 80,
			once: true,
			disable: reducedMotion,
		} );
	}

	/* --- Carruseles (Embla) ---
	 * Estructura:
	 *   [data-embla data-embla-options='{"loop":true}']
	 *     [data-embla-viewport] > .flex > slides
	 *     [data-embla-prev] [data-embla-next]   (opcionales)
	 *     ul[data-embla-dots]                    (opcional; los puntos se generan
	 *                                             según los snaps del viewport actual)
	 */
	document.querySelectorAll( '[data-embla]' ).forEach( function ( root ) {
		var viewport = root.querySelector( '[data-embla-viewport]' );

		if ( ! viewport || ! window.EmblaCarousel ) {
			return;
		}

		var options = { align: 'start', containScroll: 'trimSnaps', skipSnaps: false };
		try { Object.assign( options, JSON.parse( root.dataset.emblaOptions || '{}' ) ); } catch ( e ) {}
		if ( reducedMotion ) { options.duration = 0; }

		var embla = window.EmblaCarousel( viewport, options );
		var prev = root.querySelector( '[data-embla-prev]' );
		var next = root.querySelector( '[data-embla-next]' );
		var dotsList = root.querySelector( '[data-embla-dots]' );
		var dots = [];

		function buildDots() {
			if ( ! dotsList ) { return; }
			dotsList.innerHTML = '';
			dots = embla.scrollSnapList().map( function ( _, i ) {
				var li = document.createElement( 'li' );
				var btn = document.createElement( 'button' );
				btn.type = 'button';
				btn.innerHTML = '<span class="screen-reader-text">' + ( dotsList.dataset.label || 'Ir a' ) + ' ' + ( i + 1 ) + '</span>';
				btn.addEventListener( 'click', function () { embla.scrollTo( i ); } );
				li.appendChild( btn );
				dotsList.appendChild( li );
				return btn;
			} );
			dotsList.hidden = dots.length < 2;
		}

		function sync() {
			var index = embla.selectedScrollSnap();
			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === index );
				if ( i === index ) { dot.setAttribute( 'aria-current', 'true' ); } else { dot.removeAttribute( 'aria-current' ); }
			} );
			if ( prev ) { prev.disabled = ! embla.canScrollPrev(); }
			if ( next ) { next.disabled = ! embla.canScrollNext(); }
			var single = embla.scrollSnapList().length < 2;
			if ( prev ) { prev.hidden = single; }
			if ( next ) { next.hidden = single; }
		}

		if ( prev ) { prev.addEventListener( 'click', function () { embla.scrollPrev(); } ); }
		if ( next ) { next.addEventListener( 'click', function () { embla.scrollNext(); } ); }

		embla.on( 'init', function () { buildDots(); sync(); } );
		embla.on( 'reInit', function () { buildDots(); sync(); } );
		embla.on( 'select', sync );
		buildDots();
		sync();
	} );

	/* --- Job Board: caja de CV (solo validación en el navegador) --- */
	document.querySelectorAll( '[data-dropzone]' ).forEach( function ( zone ) {
		var input = zone.querySelector( '[data-dropzone-input]' );
		var hint = zone.querySelector( '[data-dropzone-hint]' );
		var initial = hint ? hint.textContent : '';
		var types = [ 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ];

		function accept( file ) {
			var ok = file && ( types.indexOf( file.type ) !== -1 || /\.(pdf|docx?)$/i.test( file.name ) );
			if ( ! ok ) {
				zone.classList.add( 'is-error' ); zone.classList.remove( 'is-ready' );
				if ( hint ) { hint.textContent = 'Solo PDF o Word.'; }
				return;
			}
			if ( file.size > 5 * 1024 * 1024 ) {
				zone.classList.add( 'is-error' ); zone.classList.remove( 'is-ready' );
				if ( hint ) { hint.textContent = 'El archivo supera los 5 MB.'; }
				return;
			}
			zone.classList.add( 'is-ready' ); zone.classList.remove( 'is-error' );
			if ( hint ) { hint.textContent = file.name + ' · listo para enviar'; }
		}

		if ( input ) { input.addEventListener( 'change', function () { accept( input.files[ 0 ] ); } ); }
		[ 'dragenter', 'dragover' ].forEach( function ( type ) { zone.addEventListener( type, function ( e ) { e.preventDefault(); zone.classList.add( 'is-dragover' ); } ); } );
		[ 'dragleave', 'drop' ].forEach( function ( type ) { zone.addEventListener( type, function ( e ) { e.preventDefault(); zone.classList.remove( 'is-dragover' ); } ); } );
		zone.addEventListener( 'drop', function ( e ) {
			var file = e.dataTransfer && e.dataTransfer.files[ 0 ];
			if ( file && input ) { try { input.files = e.dataTransfer.files; } catch ( err ) {} }
			accept( file );
			if ( hint && ! zone.classList.contains( 'is-ready' ) && ! zone.classList.contains( 'is-error' ) ) { hint.textContent = initial; }
		} );
	} );

	/* --- Job Board: estados de carga (login, filtros del listado) --- */
	document.querySelectorAll( '.match-login-card' ).forEach( function ( form ) {
		form.addEventListener( 'submit', function () {
			var btn = form.querySelector( 'button[type="submit"]' );
			if ( btn ) { btn.disabled = true; btn.classList.add( 'is-loading' ); }
		} );
	} );
	document.querySelectorAll( '.match-jb-filters select, .match-jb-sort select' ).forEach( function ( select ) {
		select.addEventListener( 'change', function () {
			var list = document.querySelector( '.match-jb-list, .match-jb-list-wrap' );
			if ( list ) { list.classList.add( 'is-loading' ); }
		} );
	} );

	/* --- Job Board: el CV del perfil se envía al elegir el archivo --- */
	document.querySelectorAll( '[data-autosubmit]' ).forEach( function ( input ) {
		input.addEventListener( 'change', function () {
			if ( input.files.length && input.form ) { input.form.submit(); }
		} );
	} );

	/* --- Job Board: guardar vacante ---
	 * Sin sesión lleva al login. Con sesión llama a POST match/v1/saved/{id}
	 * (inc/jobboard.php) y actualiza el marcador; en la página Guardados la
	 * tarjeta se retira y baja el contador.
	 */
	document.addEventListener( 'click', function ( event ) {
		var fav = event.target.closest( '.match-jb-card__fav' );
		if ( ! fav ) { return; }
		var cfg = window.MatchJB || {};
		if ( ! cfg.loggedIn ) { window.location.href = cfg.loginUrl || '/'; return; }
		if ( fav.disabled ) { return; }

		var wasSaved = fav.classList.contains( 'is-saved' );
		fav.classList.toggle( 'is-saved', ! wasSaved );
		fav.setAttribute( 'aria-pressed', wasSaved ? 'false' : 'true' );
		fav.disabled = true;

		fetch( cfg.rest + 'saved/' + encodeURIComponent( fav.dataset.job ), { method: 'POST', credentials: 'same-origin', headers: { 'X-WP-Nonce': cfg.nonce } } )
			.then( function ( r ) { return r.ok ? r.json() : Promise.reject( new Error( r.status ) ); } )
			.then( function ( data ) {
				fav.classList.toggle( 'is-saved', !! data.saved );
				fav.setAttribute( 'aria-pressed', data.saved ? 'true' : 'false' );
				var count = document.querySelector( '[data-saved-count]' );
				if ( count ) { count.textContent = data.count + ' ' + ( data.count === 1 ? 'vacante' : 'vacantes' ); }
				var badge = document.querySelector( '[data-nav-count="guardados"]' );
				var navItem = badge && badge.closest( '.match-jb__navitem' );
				if ( badge ) { badge.textContent = data.count; badge.hidden = ! data.count; }
				else if ( data.count && navItem === null ) {
					var guardados = Array.prototype.slice.call( document.querySelectorAll( '.match-jb__navitem' ) ).filter( function ( n ) { return /Guardados/.test( n.textContent ); } )[ 0 ];
					if ( guardados ) { var b = document.createElement( 'span' ); b.className = 'match-jb__navbadge'; b.setAttribute( 'data-nav-count', 'guardados' ); b.textContent = data.count; guardados.appendChild( b ); }
				}
				if ( ! wasSaved && data.saved ) { fav.classList.remove( 'is-pop' ); void fav.offsetWidth; fav.classList.add( 'is-pop' ); }
				var grid = fav.closest( '[data-saved-grid]' );
				if ( grid && ! data.saved ) {
					var card = fav.closest( '.match-jb-card' );
					card.classList.add( 'is-leaving' );
					window.setTimeout( function () {
						card.remove();
						if ( ! grid.querySelector( '.match-jb-card' ) ) { var empty = document.querySelector( '[data-saved-empty]' ); if ( empty ) { empty.hidden = false; } grid.hidden = true; }
					}, 250 );
				}
			} )
			.catch( function () {
				fav.classList.toggle( 'is-saved', wasSaved );
				fav.setAttribute( 'aria-pressed', wasSaved ? 'true' : 'false' );
			} )
			.then( function () { fav.disabled = false; } );
	} );

	/* --- Job Board: menú de cuenta de la topbar ---
	 * <details>/<summary> nativo: sin JS ya es accesible y no necesita
	 * posicionamiento propio. Solo se añade el cierre al hacer clic fuera,
	 * al elegir una opción o al presionar Esc, para que no se quede abierto.
	 */
	document.querySelectorAll( '.match-jb-account' ).forEach( function ( account ) {
		document.addEventListener( 'click', function ( event ) {
			if ( account.open && ! account.contains( event.target ) ) { account.open = false; }
		} );
		account.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '.match-jb-account__item' ) ) { account.open = false; }
		} );
		account.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && account.open ) {
				account.open = false;
				account.querySelector( '.match-jb-account__trigger' ).focus();
			}
		} );
	} );

	/* --- Job Board: modal de postulación ---
	 * El formulario del plugin vive en #postular, en el flujo normal de la
	 * página (así funciona sin JavaScript). "Postular ahora" traslada ese
	 * mismo nodo dentro del <dialog> y lo abre; al cerrar, vuelve a su
	 * lugar. Nunca se clona: un segundo formulario duplicaría los id y
	 * permitiría un envío doble.
	 */
	var applyModal = document.getElementById( 'match-apply-modal' );
	var applySection = document.getElementById( 'postular' );

	if ( applyModal && applySection && typeof applyModal.showModal === 'function' ) {
		var applyBody = applyModal.querySelector( '[data-apply-body]' );
		var applyHome = applySection.parentNode;

		var openApplyModal = function () {
			applyBody.appendChild( applySection );
			applyModal.showModal();
			var field = applyBody.querySelector( 'input:not([type="hidden"]), textarea, select, a.mjb-btn' );
			if ( field ) { field.focus(); }
		};

		var closeApplyModal = function () {
			if ( applySection.parentNode === applyBody ) { applyHome.appendChild( applySection ); }
			if ( applyModal.open ) { applyModal.close(); }
		};

		document.querySelectorAll( '[data-apply-trigger]' ).forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				openApplyModal();
			} );
		} );

		applyModal.querySelectorAll( '[data-apply-close]' ).forEach( function ( button ) {
			button.addEventListener( 'click', closeApplyModal );
		} );

		applyModal.addEventListener( 'click', function ( event ) {
			if ( event.target === applyModal ) { closeApplyModal(); } // clic en el fondo
		} );

		// Esc también dispara "close": si el formulario sigue dentro, se regresa.
		applyModal.addEventListener( 'close', closeApplyModal );
	}

	/* --- Job Board: modales de resultado de la postulación (éxito/error) ---
	 * El envío es un POST normal (sin fetch): class-mjb-applications.php
	 * redirige con ?mjb_applied=1 o ?mjb_error=…, y job-detail.php renderiza
	 * el <dialog> que corresponde solo si aplica. Acá solo se abre, se cierra
	 * y se limpia la URL para que un refresh no lo vuelva a mostrar.
	 */
	[ 'match-apply-success', 'match-apply-error' ].forEach( function ( id ) {
		var modal = document.getElementById( id );
		if ( ! modal || typeof modal.showModal !== 'function' ) { return; }

		modal.querySelectorAll( '[data-modal-close]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () { modal.close(); } );
		} );

		modal.addEventListener( 'click', function ( event ) {
			if ( event.target === modal ) { modal.close(); } // clic en el fondo
		} );

		// "Reintentar" (solo en el de error) abre el modal de postulación de
		// nuevo; este solo se encarga de cerrarse a sí mismo primero.
		modal.querySelectorAll( '[data-apply-trigger]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () { modal.close(); } );
		} );

		modal.showModal();
	} );

	try {
		var resultUrl = new URL( window.location.href );
		if ( resultUrl.searchParams.has( 'mjb_applied' ) || resultUrl.searchParams.has( 'mjb_error' ) ) {
			resultUrl.searchParams.delete( 'mjb_applied' );
			resultUrl.searchParams.delete( 'mjb_error' );
			window.history.replaceState( null, '', resultUrl.toString() );
		}
	} catch ( error ) { /* URL no soportada: no se limpia, no pasa nada más. */ }

	/* --- Job Board: panel de estado de la postulación ---
	 * <dialog> anclado al borde derecho (ver .match-jb-status-drawer en
	 * jobboard.css) en vez de centrado: contenido estático, así que no hace
	 * falta la relocación del modal de postulación, solo abrir/cerrar.
	 */
	var statusDrawer = document.getElementById( 'match-status-drawer' );

	if ( statusDrawer && typeof statusDrawer.showModal === 'function' ) {
		document.querySelectorAll( '[data-status-trigger]' ).forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function () {
				statusDrawer.showModal();
			} );
		} );

		statusDrawer.querySelectorAll( '[data-status-close]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () { statusDrawer.close(); } );
		} );

		statusDrawer.addEventListener( 'click', function ( event ) {
			if ( event.target === statusDrawer ) { statusDrawer.close(); } // clic en el fondo
		} );
	}

	/* --- Job Board: modal de bienvenida (una vez por usuario) ---
	 * <dialog> nativo: fondo, foco y Esc vienen de serie. Al cerrarse (o al
	 * pulsar un botón, que navega) se marca en user meta por AJAX.
	 */
	var onboarding = document.querySelector( '[data-onboarding]' );

	if ( onboarding && typeof onboarding.showModal === 'function' ) {
		var onboardingSent = false;
		var onboardingDone = function () {
			if ( onboardingSent || ! window.fetch ) { return; }
			onboardingSent = true;
			var body = new window.FormData();
			body.append( 'action', 'match_onboarding_done' );
			body.append( 'nonce', onboarding.getAttribute( 'data-onboarding-nonce' ) );
			window.fetch( onboarding.getAttribute( 'data-onboarding-url' ), { method: 'POST', body: body, credentials: 'same-origin', keepalive: true } ).catch( function () {} );
		};

		onboarding.showModal();
		if ( lenis ) { lenis.stop(); }

		onboarding.addEventListener( 'close', function () {
			if ( lenis ) { lenis.start(); }
			onboardingDone();
		} );
		onboarding.addEventListener( 'click', function ( event ) {
			if ( event.target === onboarding ) { onboarding.close(); return; } // clic en el fondo
			if ( event.target.closest( '[data-onboarding-done]' ) ) { onboardingDone(); }
		} );
	}

	/* --- Toast "Agendar consultoría": aparece tras el hero, se recuerda el cierre --- */
	var toast = document.getElementById( 'match-toast' );

	if ( toast ) {
		var dismissed = false;
		try { dismissed = window.sessionStorage.getItem( 'match-toast' ) === '1'; } catch ( e ) {}

		if ( ! dismissed ) {
			window.setTimeout( function () { toast.hidden = false; }, 1500 );
			toast.querySelector( '[data-toast-close]' ).addEventListener( 'click', function () {
				toast.hidden = true;
				try { window.sessionStorage.setItem( 'match-toast', '1' ); } catch ( e ) {}
			} );
		}
	}

	/* --- Wordmark del pie en parallax (Figma: Wordmark - match, node 3502:3533) ---
	 * Cada pieza (isotipo, luego m-a-t-c-h) sube y aparece escalonada según
	 * cuánto del pie entró en pantalla, con la curva del diseño
	 * (cubic-bezier .16,1,.3,1 ≈ expo-out). Va ligada al scroll, así que se
	 * deshace al volver arriba. El SVG trae el isotipo al final: se reordena.
	 */
	var wordmark = document.querySelector( '.match-footer__wordmark svg' );

	if ( wordmark && ! reducedMotion ) {
		var pieces = Array.prototype.slice.call( wordmark.querySelectorAll( 'path' ) );
		pieces.unshift( pieces.pop() );

		var viewBox = wordmark.viewBox.baseVal;
		var travel  = ( viewBox && viewBox.height ? viewBox.height : 24 ) * ( 20 / 128 ); // 20 px sobre 128 px del diseño, en unidades del SVG
		var ease    = function ( t ) { return t >= 1 ? 1 : 1 - Math.pow( 2, -10 * t ); };
		var ticking = false;

		var paint = function () {
			ticking = false;
			var rect = wordmark.getBoundingClientRect();
			var vh   = window.innerHeight;
			// 0 cuando el borde superior toca el fondo de la ventana; 1 cuando el logo entero está a un 15 % del fondo.
			var progress = ( vh - rect.top ) / ( rect.height + vh * 0.15 );

			pieces.forEach( function ( piece, i ) {
				var t = ease( Math.min( 1, Math.max( 0, ( progress - i * 0.08 ) / 0.5 ) ) );
				piece.style.translate = '0 ' + ( ( 1 - t ) * travel ).toFixed( 3 ) + 'px';
				piece.style.opacity   = t.toFixed( 3 );
			} );
		};

		var onScroll = function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( paint );
			}
		};

		paint();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		window.addEventListener( 'resize', onScroll );
	}

	/* --- Barra "pegada" al hacer scroll ---
	 * .is-stuck se activa cuando el centinela (48 px: franja clara + esquinas
	 * de la pestaña de la portada) sale del viewport, para que la transición
	 * no se dispare en el primer píxel de scroll.
	 */
	var navbar = document.querySelector( '.match-navbar' );

	if ( navbar && 'IntersectionObserver' in window ) {
		var sentinel = document.createElement( 'div' );
		sentinel.style.cssText = 'position:absolute;top:0;height:48px;width:1px;pointer-events:none;';
		document.body.prepend( sentinel );

		new IntersectionObserver( function ( entries ) {
			navbar.classList.toggle( 'is-stuck', ! entries[ 0 ].isIntersecting );
		} ).observe( sentinel );
	}

	/* --- Índice de las páginas legales (scroll-spy) ---
	 * Resalta en el índice el h2 que está bajo la franja superior del
	 * viewport; los enlaces son anclas normales, el clic ya lo maneja el
	 * gestor de arriba (scroll suave con Lenis si está disponible).
	 */
	var legalToc = document.querySelector( '[data-legal-toc]' );

	if ( legalToc && 'IntersectionObserver' in window ) {
		var legalLinks = Array.prototype.slice.call( legalToc.querySelectorAll( '[data-legal-toc-link]' ) );
		var legalSections = legalLinks
			.map( function ( link ) { return document.getElementById( link.hash.slice( 1 ) ); } )
			.filter( Boolean );

		var setActiveLegalLink = function ( id ) {
			legalLinks.forEach( function ( link ) {
				link.classList.toggle( 'is-active', link.hash.slice( 1 ) === id );
			} );
		};

		var legalObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) { setActiveLegalLink( entry.target.id ); }
				} );
			},
			{ rootMargin: '-96px 0px -70% 0px', threshold: 0 }
		);

		legalSections.forEach( function ( section ) { legalObserver.observe( section ); } );
		setActiveLegalLink( legalSections.length ? legalSections[ 0 ].id : '' );
	}
}() );
