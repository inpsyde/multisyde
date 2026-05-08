import { BaseControl, useBaseControlProps } from '@wordpress/components';

/**
 * Compact datetime input — replaces the default DataForm `datetime`
 * control (which renders a calendar grid + TimePicker) with a single
 * HTML5 `datetime-local` input. Values are stored as
 * `YYYY-MM-DDTHH:MM:SS` (matching what the REST controller returns
 * after the SQL space separator is stripped); the browser accepts the
 * seconds when `step="1"` is set.
 */
export default function DateTimeLocalEdit( {
	data,
	field,
	onChange,
	hideLabelFromVision,
} ) {
	const value = field.getValue( { item: data } ) || '';

	const { baseControlProps, controlProps } = useBaseControlProps( {
		__nextHasNoMarginBottom: true,
		label: hideLabelFromVision ? undefined : field.label,
		help: field.description,
	} );

	return (
		<BaseControl { ...baseControlProps }>
			<input
				{ ...controlProps }
				type="datetime-local"
				step="1"
				className="components-text-control__input"
				value={ value }
				onChange={ ( event ) =>
					onChange( { [ field.id ]: event.target.value } )
				}
			/>
		</BaseControl>
	);
}
