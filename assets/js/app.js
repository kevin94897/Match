/**
 * Match — interacciones del tema.
 * Vanilla, sin dependencias, con mejora progresiva.
 */
( function () {
	'use strict';

	/* --- Menú móvil --- */
	var toggle = document.querySelector( '.match-navbar__toggle' );
	var panel = document.getElementById( 'match-mobile-nav' );

	if ( toggle && panel ) {
		toggle.addEventListener( 'click', function () {
			var open = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
			panel.hidden = open;
			document.body.classList.toggle( 'has-open-nav', ! open );
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

	/* --- Filas de vacantes: expandir / contraer --- */
	document.querySelectorAll( '.match-job__toggle' ).forEach( function ( toggle ) {
		toggle.addEventListener( 'click', function () {
			var row = toggle.closest( '.match-job' );
			var open = row.classList.toggle( 'is-open' );
			toggle.classList.toggle( 'is-active', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
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

	/* --- Filtros de vacantes: "Borrar filtros" limpia los selects --- */
	document.querySelectorAll( '.match-vacantes__filters' ).forEach( function ( form ) {
		form.addEventListener( 'reset', function () {
			window.setTimeout( function () { form.querySelectorAll( 'select' ).forEach( function ( s ) { s.selectedIndex = 0; } ); }, 0 );
		} );
	} );

	/* --- Carrusel de casos (interna de solución) --- */
	document.querySelectorAll( '[data-carousel]' ).forEach( function ( root ) {
		var slides = Array.prototype.slice.call( root.querySelectorAll( '[data-slide]' ) );
		var dots = Array.prototype.slice.call( root.querySelectorAll( '[data-go]' ) );
		var current = 0;

		if ( slides.length < 2 ) {
			return;
		}

		function go( index ) {
			current = ( index + slides.length ) % slides.length;
			slides.forEach( function ( slide, i ) { slide.classList.toggle( 'is-active', i === current ); } );
			dots.forEach( function ( dot, i ) {
				dot.classList.toggle( 'is-active', i === current );
				if ( i === current ) { dot.setAttribute( 'aria-current', 'true' ); } else { dot.removeAttribute( 'aria-current' ); }
			} );
		}

		root.querySelector( '[data-prev]' ).addEventListener( 'click', function () { go( current - 1 ); } );
		root.querySelector( '[data-next]' ).addEventListener( 'click', function () { go( current + 1 ); } );
		dots.forEach( function ( dot ) { dot.addEventListener( 'click', function () { go( parseInt( dot.dataset.go, 10 ) ); } ); } );
	} );

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

	/* --- Sombra de la barra al hacer scroll --- */
	var navbar = document.querySelector( '.match-navbar' );

	if ( navbar && 'IntersectionObserver' in window ) {
		var sentinel = document.createElement( 'div' );
		sentinel.style.cssText = 'position:absolute;top:0;height:1px;width:1px;';
		document.body.prepend( sentinel );

		new IntersectionObserver( function ( entries ) {
			navbar.classList.toggle( 'is-stuck', ! entries[ 0 ].isIntersecting );
		} ).observe( sentinel );
	}
}() );
