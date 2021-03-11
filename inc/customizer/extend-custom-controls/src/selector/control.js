import SelectorComponent from './selector-component.js';
import { astraGetAlignmentJS } from '../common/responsive-helper';

export const selectorControl = wp.customize.astraControl.extend( {
	renderContent: function renderContent() {
		let control = this;
		ReactDOM.render( <SelectorComponent control={ control } />, control.container[0] );
	},
	ready: function() {
		astraGetAlignmentJS( this );
	},
} );
