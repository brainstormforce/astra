import { CustomPicker } from 'react-color'
import { EditableInput, Hue, Saturation, Alpha, Checkboard } from 'react-color/lib/components/common'
import { ChromePointerCircle } from 'react-color/lib/components/chrome/ChromePointerCircle'
import { ChromePointer } from 'react-color/lib/components/chrome/ChromePointer'
import PickerFields from './color-picker-fields'

export const KadenceColorPicker = ({ rgb, hex, hsv, hsl, onChange, renderers }) => {
  const styles = {
	picker: {
		width: 300,
		position: 'relative',
		marginBottom: 10,
	},
    hue: {
      height: 10,
      position: 'relative',
      marginBottom: '8px',
	},
	Hue: {
		radius: '2px',
	},
	alpha: {
        height: '10px',
        position: 'relative',
    },
	Alpha: {
   		radius: '2px',
    },
    input: {
		height: 34,
		border: `1px solid ${ hex }`,
		paddingLeft: 10,
	},
	body: {
		padding: '10px 0',
	},
	controls: {
		display: 'flex',
	},
	color: {
		width: '30px',
		height: '30px',
		position: 'relative',
		marginTop: '3px',
		marginLeft: '10px',
		borderRadius: '50%',
		overflow: 'hidden',
    },
	activeColor: {
		position: 'absolute',
		left:0,
		right:0,
		top:0,
    bottom:0,
    borderRadius: '50%',
		background: `rgba(${ rgb.r },${ rgb.g },${ rgb.b },${ rgb.a })`,
		boxShadow: 'inset 0 0 0 1px rgba(0,0,0,.1)',
	},
    swatch: {
      width: 54,
      height: 38,
      background: hex,
	},
	sliders: {
		padding: '4px 0',
		flex: '1',
	},
	saturation: {
		width: '100%',
		paddingBottom: '50%',
		position: 'relative',
		overflow: 'hidden',
	},
	Saturation: {
		radius: '2px 2px 0 0',
		shadow: 'inset 0 0 0 1px rgba(0,0,0,.15), inset 0 0 4px rgba(0,0,0,.25)',
	},
  }
  return (
    <div style={ styles.picker } className={ 'kadence-picker' }>
		<div style={ styles.saturation }>
			<Saturation
				style={ styles.Saturation }
				hsl={ hsl }
				hsv={ hsv }
				pointer={ ChromePointerCircle }
				onChange={ onChange }
			/>
		</div>
		<div style={ styles.body }>
			<div style={ styles.controls } className="flexbox-fix">
				<div style={ styles.sliders }>
					<div style={ styles.hue }>
						<Hue
							style={ styles.Hue }
							hsl={ hsl }
							onChange={ onChange }
							pointer={ ChromePointer }
						/>
					</div>
					<div style={ styles.alpha }>
						<Alpha
							style={ styles.Alpha }
							rgb={ rgb }
							hsl={ hsl }
							renderers={ renderers }
							pointer={ ChromePointer }
							onChange={ onChange }
						/>
					</div>
				</div>
				<div style={ styles.color }>
					<Checkboard />
					<div style={ styles.activeColor } />
				</div>
			</div>
		</div>
		<PickerFields
			rgb={ rgb }
			hsl={ hsl }
			hex={ hex }
			onChange={ onChange }
		/>
    </div>
  )
}

export default CustomPicker(KadenceColorPicker)