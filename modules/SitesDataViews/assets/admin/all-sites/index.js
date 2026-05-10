import domReady from '@wordpress/dom-ready';
import { createRoot } from '@wordpress/element';
import App from './App';
import './style.scss';

domReady( () => {
    const root = createRoot(
        document.getElementById( 'ms-all-sites-root' )
    );
    root.render( <App /> );
} );