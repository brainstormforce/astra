const {Dashicon, Button} = wp.components;


const ItemComponent = props => {

	let choices = ( AstraBuilderCustomizerData && AstraBuilderCustomizerData.choices && AstraBuilderCustomizerData.choices[ props.controlParams.group ] ? AstraBuilderCustomizerData.choices[ props.controlParams.group ] : [] );

	return <div className="ahfb-builder-item" data-id={props.item}
				data-section={undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : ''}
				key={props.item} onClick={() => {
		props.focusItem(undefined !== choices[props.item] && undefined !== choices[props.item].section ? choices[props.item].section : '');
	}}>
				<span className="ahfb-builder-item-text">
					{undefined !== choices[props.item] && undefined !== choices[props.item].name ? choices[props.item].name : ''}
				</span>
		<Button className="ahfb-builder-item-icon" onClick={e => {
			e.stopPropagation();
			props.removeItem(props.item);
		}}>
			<Dashicon icon="no-alt"/>
		</Button>
	</div>;
};
export default ItemComponent;
