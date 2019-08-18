<?php
/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */

class Jewellery_Lite_Control_Typography extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'typography';

	/**
	 * Array 
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Set up our control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $id
	 * @param  array   $args
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {

		// Let the parent class do its thing.
		parent::__construct( $manager, $id, $args );

		// Make sure we have labels.
		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'color'       => esc_html__( 'Font Color', 'jewellery-lite' ),
				'family'      => esc_html__( 'Font Family', 'jewellery-lite' ),
				'size'        => esc_html__( 'Font Size',   'jewellery-lite' ),
				'weight'      => esc_html__( 'Font Weight', 'jewellery-lite' ),
				'style'       => esc_html__( 'Font Style',  'jewellery-lite' ),
				'line_height' => esc_html__( 'Line Height', 'jewellery-lite' ),
				'letter_spacing' => esc_html__( 'Letter Spacing', 'jewellery-lite' ),
			)
		);
	}

	/**
	 * Enqueue scripts/styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script( 'jewellery-lite-ctypo-customize-controls' );
		wp_enqueue_style(  'jewellery-lite-ctypo-customize-controls' );
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		// Loop through each of the settings and set up the data for it.
		foreach ( $this->settings as $setting_key => $setting_id ) {

			$this->json[ $setting_key ] = array(
				'link'  => $this->get_link( $setting_key ),
				'value' => $this->value( $setting_key ),
				'label' => isset( $this->l10n[ $setting_key ] ) ? $this->l10n[ $setting_key ] : ''
			);

			if ( 'family' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_families();

			elseif ( 'weight' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_weight_choices();

			elseif ( 'style' === $setting_key )
				$this->json[ $setting_key ]['choices'] = $this->get_font_style_choices();
		}
	}

	/**
	 * Underscore JS template to handle the control's output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template() { ?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<ul>

		<# if ( data.family && data.family.choices ) { #>

			<li class="typography-font-family">

				<# if ( data.family.label ) { #>
					<span class="customize-control-title">{{ data.family.label }}</span>
				<# } #>

				<select {{{ data.family.link }}}>

					<# _.each( data.family.choices, function( label, choice ) { #>
						<option value="{{ choice }}" <# if ( choice === data.family.value ) { #> selected="selected" <# } #>>{{ label }}</option>
					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.weight && data.weight.choices ) { #>

			<li class="typography-font-weight">

				<# if ( data.weight.label ) { #>
					<span class="customize-control-title">{{ data.weight.label }}</span>
				<# } #>

				<select {{{ data.weight.link }}}>

					<# _.each( data.weight.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.weight.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.style && data.style.choices ) { #>

			<li class="typography-font-style">

				<# if ( data.style.label ) { #>
					<span class="customize-control-title">{{ data.style.label }}</span>
				<# } #>

				<select {{{ data.style.link }}}>

					<# _.each( data.style.choices, function( label, choice ) { #>

						<option value="{{ choice }}" <# if ( choice === data.style.value ) { #> selected="selected" <# } #>>{{ label }}</option>

					<# } ) #>

				</select>
			</li>
		<# } #>

		<# if ( data.size ) { #>

			<li class="typography-font-size">

				<# if ( data.size.label ) { #>
					<span class="customize-control-title">{{ data.size.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.size.link }}} value="{{ data.size.value }}" />

			</li>
		<# } #>

		<# if ( data.line_height ) { #>

			<li class="typography-line-height">

				<# if ( data.line_height.label ) { #>
					<span class="customize-control-title">{{ data.line_height.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.line_height.link }}} value="{{ data.line_height.value }}" />

			</li>
		<# } #>

		<# if ( data.letter_spacing ) { #>

			<li class="typography-letter-spacing">

				<# if ( data.letter_spacing.label ) { #>
					<span class="customize-control-title">{{ data.letter_spacing.label }} (px)</span>
				<# } #>

				<input type="number" min="1" {{{ data.letter_spacing.link }}} value="{{ data.letter_spacing.value }}" />

			</li>
		<# } #>

		</ul>
	<?php }

	/**
	 * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
	 *
	 * @todo Integrate with Google fonts.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_fonts() { return array(); }

	/**
	 * Returns the available font families.
	 *
	 * @todo Pull families from `get_fonts()`.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	function get_font_families() {

		return array(
			'' => __( 'No Fonts', 'jewellery-lite' ),
        'Abril Fatface' => __( 'Abril Fatface', 'jewellery-lite' ),
        'Acme' => __( 'Acme', 'jewellery-lite' ),
        'Anton' => __( 'Anton', 'jewellery-lite' ),
        'Architects Daughter' => __( 'Architects Daughter', 'jewellery-lite' ),
        'Arimo' => __( 'Arimo', 'jewellery-lite' ),
        'Arsenal' => __( 'Arsenal', 'jewellery-lite' ),
        'Arvo' => __( 'Arvo', 'jewellery-lite' ),
        'Alegreya' => __( 'Alegreya', 'jewellery-lite' ),
        'Alfa Slab One' => __( 'Alfa Slab One', 'jewellery-lite' ),
        'Averia Serif Libre' => __( 'Averia Serif Libre', 'jewellery-lite' ),
        'Bangers' => __( 'Bangers', 'jewellery-lite' ),
        'Boogaloo' => __( 'Boogaloo', 'jewellery-lite' ),
        'Bad Script' => __( 'Bad Script', 'jewellery-lite' ),
        'Bitter' => __( 'Bitter', 'jewellery-lite' ),
        'Bree Serif' => __( 'Bree Serif', 'jewellery-lite' ),
        'BenchNine' => __( 'BenchNine', 'jewellery-lite' ),
        'Cabin' => __( 'Cabin', 'jewellery-lite' ),
        'Cardo' => __( 'Cardo', 'jewellery-lite' ),
        'Courgette' => __( 'Courgette', 'jewellery-lite' ),
        'Cherry Swash' => __( 'Cherry Swash', 'jewellery-lite' ),
        'Cormorant Garamond' => __( 'Cormorant Garamond', 'jewellery-lite' ),
        'Crimson Text' => __( 'Crimson Text', 'jewellery-lite' ),
        'Cuprum' => __( 'Cuprum', 'jewellery-lite' ),
        'Cookie' => __( 'Cookie', 'jewellery-lite' ),
        'Chewy' => __( 'Chewy', 'jewellery-lite' ),
        'Days One' => __( 'Days One', 'jewellery-lite' ),
        'Dosis' => __( 'Dosis', 'jewellery-lite' ),
        'Droid Sans' => __( 'Droid Sans', 'jewellery-lite' ),
        'Economica' => __( 'Economica', 'jewellery-lite' ),
        'Fredoka One' => __( 'Fredoka One', 'jewellery-lite' ),
        'Fjalla One' => __( 'Fjalla One', 'jewellery-lite' ),
        'Francois One' => __( 'Francois One', 'jewellery-lite' ),
        'Frank Ruhl Libre' => __( 'Frank Ruhl Libre', 'jewellery-lite' ),
        'Gloria Hallelujah' => __( 'Gloria Hallelujah', 'jewellery-lite' ),
        'Great Vibes' => __( 'Great Vibes', 'jewellery-lite' ),
        'Handlee' => __( 'Handlee', 'jewellery-lite' ),
        'Hammersmith One' => __( 'Hammersmith One', 'jewellery-lite' ),
        'Inconsolata' => __( 'Inconsolata', 'jewellery-lite' ),
        'Indie Flower' => __( 'Indie Flower', 'jewellery-lite' ),
        'IM Fell English SC' => __( 'IM Fell English SC', 'jewellery-lite' ),
        'Julius Sans One' => __( 'Julius Sans One', 'jewellery-lite' ),
        'Josefin Slab' => __( 'Josefin Slab', 'jewellery-lite' ),
        'Josefin Sans' => __( 'Josefin Sans', 'jewellery-lite' ),
        'Kanit' => __( 'Kanit', 'jewellery-lite' ),
        'Lobster' => __( 'Lobster', 'jewellery-lite' ),
        'Lato' => __( 'Lato', 'jewellery-lite' ),
        'Lora' => __( 'Lora', 'jewellery-lite' ),
        'Libre Baskerville' => __( 'Libre Baskerville', 'jewellery-lite' ),
        'Lobster Two' => __( 'Lobster Two', 'jewellery-lite' ),
        'Merriweather' => __( 'Merriweather', 'jewellery-lite' ),
        'Monda' => __( 'Monda', 'jewellery-lite' ),
        'Montserrat' => __( 'Montserrat', 'jewellery-lite' ),
        'Muli' => __( 'Muli', 'jewellery-lite' ),
        'Marck Script' => __( 'Marck Script', 'jewellery-lite' ),
        'Noto Serif' => __( 'Noto Serif', 'jewellery-lite' ),
        'Open Sans' => __( 'Open Sans', 'jewellery-lite' ),
        'Overpass' => __( 'Overpass', 'jewellery-lite' ),
        'Overpass Mono' => __( 'Overpass Mono', 'jewellery-lite' ),
        'Oxygen' => __( 'Oxygen', 'jewellery-lite' ),
        'Orbitron' => __( 'Orbitron', 'jewellery-lite' ),
        'Patua One' => __( 'Patua One', 'jewellery-lite' ),
        'Pacifico' => __( 'Pacifico', 'jewellery-lite' ),
        'Padauk' => __( 'Padauk', 'jewellery-lite' ),
        'Playball' => __( 'Playball', 'jewellery-lite' ),
        'Playfair Display' => __( 'Playfair Display', 'jewellery-lite' ),
        'PT Sans' => __( 'PT Sans', 'jewellery-lite' ),
        'Philosopher' => __( 'Philosopher', 'jewellery-lite' ),
        'Permanent Marker' => __( 'Permanent Marker', 'jewellery-lite' ),
        'Poiret One' => __( 'Poiret One', 'jewellery-lite' ),
        'Quicksand' => __( 'Quicksand', 'jewellery-lite' ),
        'Quattrocento Sans' => __( 'Quattrocento Sans', 'jewellery-lite' ),
        'Raleway' => __( 'Raleway', 'jewellery-lite' ),
        'Rubik' => __( 'Rubik', 'jewellery-lite' ),
        'Rokkitt' => __( 'Rokkitt', 'jewellery-lite' ),
        'Russo One' => __( 'Russo One', 'jewellery-lite' ),
        'Righteous' => __( 'Righteous', 'jewellery-lite' ),
        'Slabo' => __( 'Slabo', 'jewellery-lite' ),
        'Source Sans Pro' => __( 'Source Sans Pro', 'jewellery-lite' ),
        'Shadows Into Light Two' => __( 'Shadows Into Light Two', 'jewellery-lite'),
        'Shadows Into Light' => __( 'Shadows Into Light', 'jewellery-lite' ),
        'Sacramento' => __( 'Sacramento', 'jewellery-lite' ),
        'Shrikhand' => __( 'Shrikhand', 'jewellery-lite' ),
        'Tangerine' => __( 'Tangerine', 'jewellery-lite' ),
        'Ubuntu' => __( 'Ubuntu', 'jewellery-lite' ),
        'VT323' => __( 'VT323', 'jewellery-lite' ),
        'Varela Round' => __( 'Varela Round', 'jewellery-lite' ),
        'Vampiro One' => __( 'Vampiro One', 'jewellery-lite' ),
        'Vollkorn' => __( 'Vollkorn', 'jewellery-lite' ),
        'Volkhov' => __( 'Volkhov', 'jewellery-lite' ),
        'Yanone Kaffeesatz' => __( 'Yanone Kaffeesatz', 'jewellery-lite' )
		);
	}

	/**
	 * Returns the available font weights.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_weight_choices() {

		return array(
			'' => esc_html__( 'No Fonts weight', 'jewellery-lite' ),
			'100' => esc_html__( 'Thin',       'jewellery-lite' ),
			'300' => esc_html__( 'Light',      'jewellery-lite' ),
			'400' => esc_html__( 'Normal',     'jewellery-lite' ),
			'500' => esc_html__( 'Medium',     'jewellery-lite' ),
			'700' => esc_html__( 'Bold',       'jewellery-lite' ),
			'900' => esc_html__( 'Ultra Bold', 'jewellery-lite' ),
		);
	}

	/**
	 * Returns the available font styles.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_font_style_choices() {

		return array(
			'' => esc_html__( 'No Fonts Style', 'jewellery-lite' ),
			'normal'  => esc_html__( 'Normal', 'jewellery-lite' ),
			'italic'  => esc_html__( 'Italic', 'jewellery-lite' ),
			'oblique' => esc_html__( 'Oblique', 'jewellery-lite' )
		);
	}
}
