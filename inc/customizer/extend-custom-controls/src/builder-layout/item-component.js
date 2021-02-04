const {Dashicon, Button} = wp.components;
const {__} = wp.i18n;


const ItemComponent = props => {

	let choices = (AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[props.controlParams.group] ? AstraBuilderCustomizerData.choices[props.controlParams.group] : []);

	const deleteItem = (props) => {


		sessionStorage.setItem('astra-builder-eradicate-in-progress', true);

		var event = new CustomEvent('AstraBuilderDeleteSectionControls', {
			'detail': choices[props.item]
		});
		document.dispatchEvent(event);

		let forceRemoveSection = choices[props.item];
		delete choices[props.item];

		const component_track = wp.customize('astra-settings[cloned-component-track]').get();

		let removing_index= parseInt( forceRemoveSection.section.match(/\d+$/)[0] );
		let existing_component_count = component_track[ forceRemoveSection.builder + '-' + forceRemoveSection.type ];
		let finalArray = component_track['removed-items'];

		// In removing last element.
		if( removing_index != parseInt( AstraBuilderCustomizerData.component_limit ) ) {
			finalArray.push(forceRemoveSection.section);
		}

		finalArray = finalArray.filter(function(el, index, arr) {
			return index == arr.indexOf(el);
		});

		// If removing last item.
		if( existing_component_count == removing_index  ) {
			while (true) {
				existing_component_count = existing_component_count - 1;
				component_track[ forceRemoveSection.builder + '-' + forceRemoveSection.type ] = existing_component_count;

				var index = finalArray.indexOf( forceRemoveSection.section.replace(/[0-9]+/g, existing_component_count) );
				if (index !== -1) {
					finalArray.splice(index, 1);
				} else {
					var index = finalArray.indexOf( forceRemoveSection.section.replace(/[0-9]+/g, removing_index) );
					if (index !== -1) {
						finalArray.splice(index, 1);
					}
					break;
				}
			}
		}

		wp.customize('astra-settings[cloned-component-track]').set( { ...component_track,
				'removed-items': finalArray,
				flag: ! component_track.flag
			} );

	}

	return <div className="ahfb-builder-item" data-id={props.item}
				data-section={undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : ''}
				key={props.item} onClick={() => {
		props.focusItem(undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : '');
	}}>
				<span className="ahfb-builder-item-text">
					{undefined !== choices[props.item] && undefined !== choices[props.item].name ? choices[props.item].name : ''}
				</span>
		{
			astra.customizer.is_pro &&
			<div className="ahfb-slide-up">
				{ choices[props.item]['clone'] && <span data-tooltip={__('Clone element', 'astra')}
					  onClick={e => {
						  e.stopPropagation();

						  // Skip clone if already is in progress.
						  if( sessionStorage.getItem('astra-builder-clone-in-progress') ) {
							  return;
						  }

						  props.cloneItem(props.item);
					  }} className="dashicons dashicons-admin-page">
				</span> }
				<span data-tooltip={__('Reset element', 'astra')}
					  onClick={e => {
						  e.stopPropagation();

						  // Skip Reset if already is in progress.
						  if( sessionStorage.getItem('astra-builder-reset-in-progress') ) {
							  return;
						  }

						  var event = new CustomEvent('AstraBuilderResetSectionControls', {
							  'detail': {
							  	'section_id': choices[props.item].section
							  }
						  });
						  document.dispatchEvent(event);
					  }}
					  className="dashicons dashicons-image-rotate">
				</span>

				{ choices[props.item]['delete'] &&

				<span data-tooltip={ __('Delete element from customizer', 'astra') }
					  onClick={e => {

						  // Skip Delete if already is in progress.
						  if( sessionStorage.getItem('astra-builder-eradicate-in-progress') ) {
							  return;
						  }

						  e.stopPropagation();
						  deleteItem(props);
						  props.removeItem(props.item);
					  }}
					  className="dashicons dashicons-trash">
				</span>

				}
			</div>
		}

		<Button className="ahfb-builder-item-icon" onClick={e => {
			e.stopPropagation();
			props.removeItem(props.item);
		}}>
			<Dashicon data-tooltip={ __('Remove element from grid', 'astra') } icon="no-alt"/>
		</Button>
	</div>;
};
export default ItemComponent;
