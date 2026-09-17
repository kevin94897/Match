/**
 * Copia a assets/vendor los archivos de AOS, Embla y Lenis que el tema encola.
 * Se ejecuta con `npm run vendor` (incluido en `npm run build`).
 */
import { copyFileSync, mkdirSync } from 'node:fs';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );
const files = {
	'node_modules/aos/dist/aos.css': 'assets/vendor/aos.css',
	'node_modules/aos/dist/aos.js': 'assets/vendor/aos.js',
	'node_modules/embla-carousel/embla-carousel.umd.js': 'assets/vendor/embla-carousel.umd.js',
	'node_modules/lenis/dist/lenis.min.js': 'assets/vendor/lenis.min.js',
	'node_modules/lenis/dist/lenis.css': 'assets/vendor/lenis.css',
};

mkdirSync( resolve( root, 'assets/vendor' ), { recursive: true } );
for ( const [ from, to ] of Object.entries( files ) ) {
	copyFileSync( resolve( root, from ), resolve( root, to ) );
	console.log( `${ from } → ${ to }` );
}
