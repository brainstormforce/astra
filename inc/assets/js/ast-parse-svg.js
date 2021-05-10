function parseSVG ( svg ) {

	svg = svg.replace( "far ", "" )
	svg = svg.replace( "fas ", "" )
	svg = svg.replace( "fab ", "" )
	svg = svg.replace( "fa-", "" )
	svg = svg.replace( "fa ", "" )

	return svg
}

export default parseSVG
