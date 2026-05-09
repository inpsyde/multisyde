import {
	BaseControl,
	Button,
	Dropdown,
	DateTimePicker,
	useBaseControlProps,
} from '@wordpress/components';
import { dateI18n, getSettings } from '@wordpress/date';
import { __ } from '@wordpress/i18n';

/**
 * Compact datetime control — renders a single button showing the
 * value formatted in the WordPress site locale; clicking opens a
 * popover with the full `DateTimePicker` (calendar + time inputs).
 *
 * Values are stored as `YYYY-MM-DDTHH:MM:SS` (matching what the REST
 * controller returns after the SQL space separator is stripped).
 */
export default function DateTimePopoverEdit( {
	data,
	field,
	onChange,
	hideLabelFromVision,
} ) {
	const value = field.getValue( { item: data } ) || '';

	const { baseControlProps } = useBaseControlProps( {
		__nextHasNoMarginBottom: true,
		label: hideLabelFromVision ? undefined : field.label,
		help: field.description,
	} );

	const { formats } = getSettings();
	const is12Hour = /[gh]/.test( formats.time );
	const buttonLabel = value
		? dateI18n( `${ formats.date } ${ formats.time }`, value )
		: __( 'Select date & time', 'multisyde' );

	return (
		<BaseControl { ...baseControlProps }>
			<Dropdown
				className="ms-datetime-popover"
				contentClassName="ms-datetime-popover__content"
				popoverProps={ { placement: 'bottom-start' } }
				renderToggle={ ( { isOpen, onToggle } ) => (
					<Button
						variant="tertiary"
						onClick={ onToggle }
						aria-expanded={ isOpen }
						aria-haspopup="dialog"
					>
						{ buttonLabel }
					</Button>
				) }
				renderContent={ () => (
					<DateTimePicker
						currentDate={ value || null }
						is12Hour={ is12Hour }
						onChange={ ( next ) =>
							onChange( {
								[ field.id ]: next
									? next.replace(
											/([+-]\d{2}:?\d{2}|Z)$/,
											''
									  )
									: '',
							} )
						}
					/>
				) }
			/>
		</BaseControl>
	);
}
