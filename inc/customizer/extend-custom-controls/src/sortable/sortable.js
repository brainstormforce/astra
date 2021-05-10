import PropTypes from 'prop-types';

const SortableComponent = props => {

	let labelHtml = null,
		descriptionHtml = null;

	const {
		label,
		description,
		value,
		choices,
		inputAttrs
	} = props.control.params;

	if (label) {
		labelHtml = <span className="customize-control-title">{label}</span>;
	}

	if (description) {
		descriptionHtml = <span className="description customize-control-description">{description}</span>;
	}

	let visibleMetaHtml = Object.values(value).map(choiceID => {
		let html = '';
		if (choices[choiceID]) {
			html = <div {...inputAttrs} key={choiceID} className='ast-sortable-item' data-value={choiceID}>
				{choices[choiceID]}
				<i className="dashicons dashicons-visibility visibility"></i>
			</div>;
		}
		return html;
	});

	let invisibleMetaHtml = Object.keys(choices).map(choiceID => {
		let html = '';
		if (Array.isArray(value) && -1 === value.indexOf(choiceID)) {
			html = <div {...inputAttrs} key={choiceID} className='ast-sortable-item invisible' data-value={choiceID}>
				{choices[choiceID]}
				<i className="dashicons dashicons-visibility visibility"></i>
			</div>;
		}
		return html;
	});

	return <label className='ast-sortable'>
		{labelHtml}
		{descriptionHtml}
		<div className="sortable">
			{visibleMetaHtml}
			{invisibleMetaHtml}
		</div>
	</label>;

};

SortableComponent.propTypes = {
	control: PropTypes.object.isRequired
};

export default React.memo( SortableComponent );
