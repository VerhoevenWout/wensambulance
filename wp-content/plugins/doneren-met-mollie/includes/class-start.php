<?php

class Dmm_Start {

    private $wpdb;

    /**
     * Dmm_Start constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('init', array($this, 'dmm_do_output_buffer'));
        add_filter('plugin_action_links_' . DMM_PLUGIN_BASE, array($this, 'dmm_settings_links'));
        add_shortcode('doneren_met_mollie', array($this, 'dmm_donate_form'));
        add_shortcode('doneren_met_mollie_total', array($this, 'dmm_donate_total'));
        add_shortcode('doneren_met_mollie_goal', array($this, 'dmm_donate_goal'));

        // Variable translations
        __('iDEAL', 'doneren-met-mollie');
        __('Creditcard', 'doneren-met-mollie');
        __('Bancontact/Mister Cash', 'doneren-met-mollie');
        __('SOFORT Banking', 'doneren-met-mollie');
        __('Bank transfer', 'doneren-met-mollie');
        __('SEPA Direct Debit', 'doneren-met-mollie');
        __('Belfius Direct Net', 'doneren-met-mollie');
        __('PayPal', 'doneren-met-mollie');
        __('Bitcoin', 'doneren-met-mollie');
        __('PODIUM Cadeaukaart', 'doneren-met-mollie');
        __('Paysafecard', 'doneren-met-mollie');
    }

    /**
     * Install/upgrade database
     *
     * @since 1.0.0
     */
    public function dmm_install_database()
    {
        $table_name = DMM_TABLE_DONATIONS;
        $table_donors = DMM_TABLE_DONORS;
        $table_subscriptions = DMM_TABLE_SUBSCRIPTIONS;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sqlDonations = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            dm_amount float(15) NOT NULL,
            payment_id varchar(45) NOT NULL,
            customer_id varchar(45),
            subscription_id varchar(45),
            payment_method varchar(45) NOT NULL,
            payment_mode varchar(45) NOT NULL,
            donation_id varchar(45) NOT NULL,
            dm_status varchar(25) NOT NULL,
            dm_name varchar(255) NOT NULL,
            dm_email varchar(255) NOT NULL,
            dm_phone varchar(255) NOT NULL,
            dm_company varchar(255) NOT NULL,
            dm_project varchar(255) NOT NULL,
            dm_address varchar(255) NOT NULL,
            dm_zipcode varchar(255) NOT NULL,
            dm_city varchar(255) NOT NULL,
            dm_country varchar(255) NOT NULL,
            dm_message text NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlDonations);

        $sqlDonors = "CREATE TABLE $table_donors (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            customer_id varchar(45) NOT NULL,
            customer_mode varchar(45) NOT NULL,
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            sub_interval varchar(255) NOT NULL,
            sub_amount float(15) NOT NULL,
            sub_description varchar(255) NOT NULL,
            customer_locale varchar(15) NOT NULL,
            secret varchar(45) NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlDonors);

        $sqlSubscriptions = "CREATE TABLE $table_subscriptions (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            subscription_id varchar(45) NOT NULL,
            customer_id varchar(45) NOT NULL,
            sub_mode varchar(45) NOT NULL,
            sub_amount float(15) NOT NULL,
            sub_times int(9) NOT NULL,
            sub_interval varchar(45) NOT NULL,
            sub_description varchar(255) NOT NULL,
            sub_method varchar(45) NOT NULL,
            sub_status varchar(25) NOT NULL,
            created_at timestamp NOT NULL,
            UNIQUE KEY id (id)
        );";
        dbDelta($sqlSubscriptions);

        update_option('dmm_version', DMM_VERSION);
    }

    /**
     * Settings link in plugin list
     *
     * @since 1.0.0
     * @param $links
     * @return mixed
     */
    public function dmm_settings_links($links) {
        $settings_link = '<a href="admin.php?page=' . DMM_PAGE_SETTINGS . '">' . __('Settings', 'doneren-met-mollie') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Output buffer for redirects
     *
     * @since 1.0.0
     */
    public function dmm_do_output_buffer() {
        ob_start();
    }

    /**
     * Shortcode for total donations
     *
     * @since 2.3.0
     */
    public function dmm_donate_total()
    {
        ob_start();
        $sum = $this->wpdb->get_var("SELECT SUM(dm_amount) FROM " . DMM_TABLE_DONATIONS . " WHERE dm_status='paid' AND payment_mode='live'");
        echo '&euro;' . number_format($sum, 2, ',', '');

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Shortcode for goal donations
     *
     * @param $atts
     * @since 2.4.8
     *
     * @return string
     */
    public function dmm_donate_goal($atts)
    {
        $atts = shortcode_atts(array(
            'goal'  => '',
            'text'  => __('Goal reached!', 'doneren-met-mollie'),
        ), $atts);

        ob_start();

        if ($atts['goal'] < 0)
            echo __('Goal must be higher then 0', 'doneren-met-mollie');
        else
        {
            $sum = $this->wpdb->get_var("SELECT SUM(dm_amount) FROM " . DMM_TABLE_DONATIONS . " WHERE dm_status='paid' AND payment_mode='live'");

            $goal = (int) $atts['goal'] - $sum;

            if ($goal <= 0)
                echo __($atts['text'], 'doneren-met-mollie');
            else
                echo '&euro;' . number_format($goal, 2, ',', '');
        }

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Donation form
     *
     * @since 1.0.0
     * @return string
     */
    public function dmm_donate_form()
    {
        ob_start();

        try {
            // Connect with Mollie
            $mollie = new Mollie_API_Client;
            if (get_option('dmm_mollie_apikey'))
                $mollie->setApiKey(get_option('dmm_mollie_apikey'));
            else
                return __('No API-key set', 'doneren-met-mollie');


            $dmm_webhook = get_home_url(null, DMM_WEBHOOK);
            $dmm_fields = get_option('dmm_form_fields');

            // Submit form, add donation
            if (isset($_POST['dmm_submitted'])) {

                // Validation
                $errors = array();
                if (((isset($dmm_fields['Name']['required']) && $dmm_fields['Name']['required']) || $_POST['dmm_recurring_interval'] != 'one') && empty($_POST['dmm_name']))
                    $errors[] = __('Your name is required', 'doneren-met-mollie');

                if (((isset($dmm_fields['Email address']['required']) && $dmm_fields['Email address']['required']) || $_POST['dmm_recurring_interval'] != 'one') && empty($_POST['dmm_email']))
                    $errors[] = __('Your email address is required', 'doneren-met-mollie');

                if ($_POST['dmm_recurring_interval'] != 'one' && !isset($_POST['dmm_permission']))
                    $errors[] = __('Please give authorization to collect from your account', 'doneren-met-mollie');

                if (isset($dmm_fields['Phone number']['required']) && $dmm_fields['Phone number']['required'] && empty($_POST['dmm_phone']))
                    $errors[] = __('Your phone number is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Company name']['required']) && $dmm_fields['Company name']['required'] && empty($_POST['dmm_company']))
                    $errors[] = __('Your company name is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] && empty($_POST['dmm_address']))
                    $errors[] = __('Your address is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] && empty($_POST['dmm_city']))
                    $errors[] = __('Your city is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] && empty($_POST['dmm_zipcode']))
                    $errors[] = __('Your zipcode is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] && empty($_POST['dmm_country']))
                    $errors[] = __('Your country is required', 'doneren-met-mollie');

                if (isset($dmm_fields['Message']['required']) && $dmm_fields['Message']['required'] && empty($_POST['dmm_message']))
                    $errors[] = __('A message is required', 'doneren-met-mollie');

                if (empty($_POST['dmm_amount']))
                    $errors[] = __('Please choose an amount', 'doneren-met-mollie');

                if ($_POST['dmm_amount'] < (float)get_option('dmm_minimum_amount', 1))
                    $errors[] = __('The amount is too low, please choose a higher amount', 'doneren-met-mollie');

                if (!empty($errors))
                {
                    echo '<ul>';
                    foreach ($errors as $error) {
                        echo '<li style="color: red;">' . $error . '</li>';
                    }
                    echo '</ul><br>';
                }
                else
                {
                    $donation_id = uniqid(rand(1,99));
                    $amount = number_format(str_replace(',', '.', $_POST['dmm_amount']), 2, '.', '');

                    $interval = '';
                    if (isset($_POST['dmm_recurring_interval']))
                    {
                        switch($_POST['dmm_recurring_interval'])
                        {
                            case 'one':
                                $interval = __('One-time donation', 'doneren-met-mollie');
                                break;
                            case 'month':
                                $interval = __('Monthly', 'doneren-met-mollie');
                                break;
                            case 'quarter':
                                $interval = __('Each quarter', 'doneren-met-mollie');
                                break;
                            case 'year':
                                $interval = __('Annually', 'doneren-met-mollie');
                                break;
                        }
                    }

                    $description = str_replace(
                        array(
                            '{id}',
                            '{name}',
                            '{project}',
                            '{amount}',
                            '{company}',
                            '{email}',
                            '{interval}',
                        ),
                        array(
                            $donation_id,
                            isset($_POST['dmm_name']) ? $_POST['dmm_name'] : '',
                            isset($_POST['dmm_project']) ? $_POST['dmm_project'] : '',
                            $amount,
                            isset($_POST['dmm_company']) ? $_POST['dmm_company'] : '',
                            isset($_POST['dmm_email']) ? $_POST['dmm_email'] : '',
                            $interval,
                        ),
                        get_option('dmm_payment_description')
                    );


                    if(is_home())
                        $redirectBaseUrl = home_url() . '/';
                    else
                        $redirectBaseUrl = get_page_link();

                    if ($_POST['dmm_recurring_interval'] == 'one')
                    {
                        // One-time donation
                        $payment = $mollie->payments->create(array(
                            "amount"        => $amount,
                            "description"   => $description,
                            "redirectUrl"   => $redirectBaseUrl . '?dmm_id=' . $donation_id,
                            "webhookUrl"    => $dmm_webhook,
                            "method"        => $_POST['dmm_method'],
                            "metadata"      => array(
                                "name"  => isset($_POST['dmm_name']) ? $_POST['dmm_name'] : '',
                                "email" => isset($_POST['dmm_email']) ? $_POST['dmm_email'] : '',
                                "company"       => isset($_POST['dmm_company']) ? $_POST['dmm_company'] : '',
                                "donation_id"   => $donation_id,
                            )
                        ));
                    }
                    else
                    {
                        $secret = uniqid();
                        $customer = $mollie->customers->create(array(
                            "name"  => isset($_POST['dmm_name']) ? $_POST['dmm_name'] : '',
                            "email" => isset($_POST['dmm_email']) ? $_POST['dmm_email'] : '',
                        ));

                        do_action('dmm_customer_created', $customer);

                        $this->wpdb->query($this->wpdb->prepare("INSERT INTO " . DMM_TABLE_DONORS . "
                    ( customer_id, customer_mode, customer_name, customer_email, sub_interval, sub_amount, sub_description, customer_locale, secret )
                    VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s )",
                            $customer->id,
                            $customer->mode,
                            $customer->name,
                            $customer->email,
                            $_POST['dmm_recurring_interval'],
                            $amount,
                            $description,
                            $customer->locale,
                            $secret
                        ));

                        $payment = $mollie->payments->create(array(
                            'amount'        => $amount,
                            'customerId'    => $customer->id,
                            'recurringType' => 'first',
                            "description"   => $description,
                            "redirectUrl"   => $redirectBaseUrl . '?dmm_id=' . $donation_id,
                            "webhookUrl"    => $dmm_webhook . 'first/' . $this->wpdb->insert_id . '/secret/' . $secret,
                            "method"        => $_POST['dmm_method'],
                            "metadata"      => array(
                                "name"  => isset($_POST['dmm_name']) ? $_POST['dmm_name'] : '',
                                "email" => isset($_POST['dmm_email']) ? $_POST['dmm_email'] : '',
                                "company"       => isset($_POST['dmm_company']) ? $_POST['dmm_company'] : '',
                                "donation_id"   => $donation_id,
                            )
                        ));
                    }

                    do_action('dmm_payment_created', $payment);

                    $this->wpdb->query($this->wpdb->prepare("INSERT INTO " . DMM_TABLE_DONATIONS . "
                    ( `time`, payment_id, customer_id, donation_id, dm_status, dm_amount, dm_name, dm_email, dm_project, dm_company, dm_address, dm_zipcode, dm_city, dm_country, dm_message, dm_phone, payment_method, payment_mode )
                    VALUES ( %s, %s, %s, %s, 'open', %f, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
                        date('Y-m-d H:i:s'),
                        $payment->id,
                        (isset($customer) ? $customer->id : null),
                        $donation_id,
                        $amount,
                        isset($_POST['dmm_name']) ? $_POST['dmm_name'] : null,
                        isset($_POST['dmm_email']) ? $_POST['dmm_email'] : null,
                        isset($_POST['dmm_project']) ? $_POST['dmm_project'] : null,
                        isset($_POST['dmm_company']) ? $_POST['dmm_company'] : null,
                        isset($_POST['dmm_address']) ? $_POST['dmm_address'] : null,
                        isset($_POST['dmm_zipcode']) ? $_POST['dmm_zipcode'] : null,
                        isset($_POST['dmm_city']) ? $_POST['dmm_city'] : null,
                        isset($_POST['dmm_country']) ? $_POST['dmm_country'] : null,
                        isset($_POST['dmm_message']) ? $_POST['dmm_message'] : null,
                        isset($_POST['dmm_phone']) ? $_POST['dmm_phone'] : null,
                        $payment->method,
                        $payment->mode
                    ));

                    wp_redirect($payment->getPaymentUrl());
                    exit;
                }

            }

            // Return page
            if (isset($_GET['dmm_id'])) {
                $donation = $this->wpdb->get_row("SELECT * FROM " . DMM_TABLE_DONATIONS . " WHERE donation_id = '" . esc_sql($_GET['dmm_id']) . "'");
                $payment = $mollie->payments->get($donation->payment_id);


                if ($payment->status == 'paid')
                {
                    if (!isset($_GET['dmm_redirect']))
                    {
                        wp_redirect(get_option('dmm_redirect_success') != '-1' ? get_permalink( $this->get_page_id_by_slug( get_option('dmm_redirect_success') ) ) : get_page_link() . '?dmm_redirect=true&dmm_id=' . $_GET['dmm_id']);
                        exit;
                    }

                    echo '<p class="' . esc_attr(get_option('dmm_success_cls', DMM_SUCCESS_CLS)) . '">' . esc_html__('Thank you for your donation!', 'doneren-met-mollie') . '</p>';
                }
                else
                {
                    if (!isset($_GET['dmm_redirect']))
                    {
                        wp_redirect(get_option('dmm_redirect_failure') != '-1' ? get_permalink( $this->get_page_id_by_slug( get_option('dmm_redirect_failure') ) ) : get_page_link() . '?dmm_redirect=true&dmm_id=' . $_GET['dmm_id']);
                        exit;
                    }

                    echo '<p class="' . esc_attr(get_option('dmm_failure_cls', DMM_FAILURE_CLS)) . '">' . esc_html__('The payment was not successful, please try again.', 'doneren-met-mollie') . '</p>';
                }
            } else {
                // Donation form

                $intervals = get_option('dmm_recurring_interval');

                $selected_interval = isset($_POST['dmm_recurring_interval']) ? $_POST['dmm_recurring_interval'] : get_option('dmm_default_interval');
                ?>
                <form action="<?php echo esc_url($_SERVER['REQUEST_URI']);?>" class="<?php echo esc_attr(get_option('dmm_form_cls'));?>" method="post">

                    <?php if (get_option('dmm_recurring')) { ?>
                        <p>
                            <select id="dmm_interval" name="dmm_recurring_interval" style="width: 100%" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" onchange="dmm_recurring_methods(this.value);">
                                <option value="one"><?php echo esc_html_e('One-time donation', 'doneren-met-mollie');?></option>
                                <?php if (isset($intervals['month'])) { ?>
                                    <option value="month" <?php echo ($selected_interval == 'month' ? 'selected' : '');?>><?php echo esc_html_e('Monthly', 'doneren-met-mollie');?></option>
                                <?php } ?>
                                <?php if (isset($intervals['quarter'])) { ?>
                                    <option value="quarter" <?php echo ($selected_interval == 'quarter' ? 'selected' : '');?>><?php echo esc_html_e('Each quarter', 'doneren-met-mollie');?></option>
                                <?php } ?>
                                <?php if (isset($intervals['year'])) { ?>
                                    <option value="year" <?php echo ($selected_interval == 'year' ? 'selected' : '');?>><?php echo esc_html_e('Annually', 'doneren-met-mollie');?></option>
                                <?php } ?>
                            </select>
                        </p>
                    <?php } else { ?>
                        <input type="hidden" name="dmm_recurring_interval" value="one">
                    <?php } ?>

                    <?php if (isset($dmm_fields['Name']['active']) && $dmm_fields['Name']['active']) { ?>
                        <p <?php echo ($dmm_fields['Name']['active'] ? '' : 'style="display:none"');?>>
                            <?php echo esc_html_e('Name', 'doneren-met-mollie') . (isset($dmm_fields['Name']['required']) && $dmm_fields['Name']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_name" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_name"]) ? esc_attr($_POST["dmm_name"]) : '');?>" style="width: 100%">
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Company name']['active']) && $dmm_fields['Company name']['active']) { ?>
                        <p>
                            <?php echo esc_html_e('Company name', 'doneren-met-mollie') . (isset($dmm_fields['Company name']['required']) && $dmm_fields['Company name']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_company" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_company"]) ? esc_attr($_POST["dmm_company"]) : '');?>" style="width: 100%">
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Email address']['active']) && $dmm_fields['Email address']['active']) { ?>
                        <p <?php echo ($dmm_fields['Email address']['active'] ? '' : 'style="display:none"');?>>
                            <?php echo esc_html_e('Email address', 'doneren-met-mollie') . (isset($dmm_fields['Email address']['required']) && $dmm_fields['Email address']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_email" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_email"]) ? esc_attr($_POST["dmm_email"]) : '');?>" style="width: 100%">
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Phone number']['active']) && $dmm_fields['Phone number']['active']) { ?>
                        <p>
                            <?php echo esc_html_e('Phone number', 'doneren-met-mollie') . (isset($dmm_fields['Phone number']['required']) && $dmm_fields['Phone number']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_phone" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_phone"]) ? esc_attr($_POST["dmm_phone"]) : '');?>" style="width: 100%">
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Address']['active']) && $dmm_fields['Address']['active']) { ?>
                        <p>
                            <?php echo esc_html_e('Address', 'doneren-met-mollie') . (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_address" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_address"]) ? esc_attr($_POST["dmm_address"]) : '');?>" style="width: 100%">
                        </p>
                        <p>
                            <?php echo esc_html_e('Zipcode', 'doneren-met-mollie') . (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_zipcode" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_zipcode"]) ? esc_attr($_POST["dmm_zipcode"]) : '');?>" style="width: 100%">
                        </p>
                        <p>
                            <?php echo esc_html_e('City', 'doneren-met-mollie') . (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_city" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_city"]) ? esc_attr($_POST["dmm_city"]) : '');?>" style="width: 100%">
                        </p>
                        <p>
                            <?php echo esc_html_e('Country', 'doneren-met-mollie') . (isset($dmm_fields['Address']['required']) && $dmm_fields['Address']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <input type="text" name="dmm_country" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" value="<?php echo (isset($_POST["dmm_country"]) ? esc_attr($_POST["dmm_country"]) : '');?>" style="width: 100%">
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Project']['active']) && $dmm_fields['Project']['active']) { ?>
                        <p>
                            <?php echo esc_html_e('Project', 'doneren-met-mollie') . (isset($dmm_fields['Project']['required']) && $dmm_fields['Project']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <?php echo $this->dmm_projects(isset($_POST["dmm_project"]) ? esc_attr($_POST["dmm_project"]) : '');?>
                        </p>
                    <?php } ?>

                    <?php if (isset($dmm_fields['Message']['active']) && $dmm_fields['Message']['active']) { ?>
                        <p>
                            <?php echo esc_html_e('Message', 'doneren-met-mollie') . (isset($dmm_fields['Message']['required']) && $dmm_fields['Message']['required'] ? '<span style="color:red;">*</span>' : '') . '<br>';?>
                            <textarea name="dmm_message" class="<?php echo esc_attr(get_option('dmm_fields_cls'));?>" rows="5" style="width: 100%"><?php echo (isset($_POST["dmm_message"]) ? esc_attr($_POST["dmm_message"]) : '');?></textarea>
                        </p>
                    <?php } ?>

                    <p>
                        <?php
                        echo esc_html_e('Amount', 'doneren-met-mollie') . ' &euro;<span style="color:red;">*</span><br>';

                        if (get_option('dmm_amount'))
                        {
                            if (get_option('dmm_free_input'))
                            {
                                echo '<select id="dmm_dd" style="width: 100%" class="' . esc_attr(get_option('dmm_fields_cls')) . '" onchange="if(this.value!=\'--\'){document.getElementById(\'dmm_amount\').value=this.value;document.getElementById(\'dmm_amount\').style.display = \'none\';}else{document.getElementById(\'dmm_amount\').style.display = \'block\';}">';
                                echo '<option value="--">' . esc_html__('Enter your own amount', 'doneren-met-mollie') . '</option>';
                            }
                            else
                            {
                                echo '<select style="width: 100%" name="dmm_amount" class="' . esc_attr(get_option('dmm_fields_cls')) . '" >';
                            }

                            foreach (explode('/', get_option('dmm_amount')) as $amount) {
                                echo '<option value="' . trim(esc_attr($amount)) . '"' . (get_option('dmm_default_amount') == trim($amount) ? ' selected' : '') . '>&euro; ' . esc_html($amount) . '</option>';
                            }
                            echo '</select>';
                        }

                        if (get_option('dmm_free_input'))
                        {
                            echo '<input type="text" id="dmm_amount" name="dmm_amount" class="' . esc_attr(get_option('dmm_fields_cls')) . '" value="' . (isset($_POST["dmm_amount"]) ? esc_attr($_POST["dmm_amount"]) : get_option('dmm_default_amount')) . '" style="width: 100%">';
                        }
                        ?>
                    </p>

                    <?php echo $this->dmm_payment_methods($mollie);?>

                    <br>
                    <script>
                        window.onload=function() {
                            var dmm_dd = document.getElementById('dmm_dd');
                            if(dmm_dd !== null) {
                                if (dmm_dd.value !== '--') {
                                    document.getElementById('dmm_amount').value=document.getElementById('dmm_dd').value;
                                    document.getElementById('dmm_amount').style.display = 'none';
                                }
                            }
                            <?php if (get_option('dmm_recurring')) { ?>
                                if(document.getElementById('dmm_interval').value !== 'one'){
                                    document.getElementById('dmm_permission').style.display = 'block';
                                }
                                dmm_recurring_methods(document.getElementById('dmm_interval').value);
                            <?php } ?>
                        }
                    </script>
                    <label id="dmm_permission" style="display:none"><input type="checkbox" name="dmm_permission"> <?php echo sprintf(__('I hereby authorize %s to collect the amount shown above from my account periodically.', 'doneren-met-mollie'), get_option('dmm_name_foundation'));?></label>

                    <input type="submit" name="dmm_submitted" class="<?php echo esc_attr(get_option('dmm_button_cls'));?>" value="<?php echo esc_attr(__('Donate', 'doneren-met-mollie'));?>">

                </form>
                <?php

            }


        } catch (Mollie_API_Exception $e) {
            echo "API call failed: " . htmlspecialchars($e->getMessage());
        }

        $output = ob_get_clean();
        return $output;
    }

    /**
     * Payment methods
     *
     * @since 2.0.0
     * @param $mollie
     * @return string
     */
    private function dmm_payment_methods($mollie) {
        $option = get_option('dmm_methods_display', 'list');
        $methods = '';

        if (get_option('dmm_recurring'))
        {
            $recurring = array('dd' => false, 'cc' => false);
            foreach ($mollie->methods->all(0,50,array('recurringType' => 'recurring')) as $method)
            {
                if ($method->id == 'directdebit')$recurring['dd'] = true;
                if ($method->id == 'creditcard')$recurring['cc'] = true;
            }

            $scriptCC = '';
            if (!$recurring['cc'])
            {
                $scriptCC = '
                var x = document.getElementsByClassName("dmm_cc");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = value!="one" ? "none" : "block";
                    x[i].disabled = value!="one" ? "disabled" : "";
                }';
            }

            $scriptDD = '';
            if (!$recurring['dd'])
            {
                $scriptDD = '
                var x = document.getElementsByClassName("dmm_dd");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = value!="one" ? "none" : "block";
                    x[i].disabled = value!="one" ? "disabled" : "";
                }';
            }

            $methods .= '
            <script>
            function dmm_recurring_methods(value) {
                var x = document.getElementsByClassName("dmm_recurring");
                var i;
                for (i = 0; i < x.length; i++) {
                    x[i].style.display = value!="one" ? "none" : "block";
                    x[i].disabled = value!="one" ? "disabled" : "";
                }
                ' . $scriptCC . $scriptDD . '
                document.getElementById("dmm_permission").style.display = (value=="one" ? "none" : "block");
            }
            </script>';
        }

        $first = true;
        if ($option == 'list')
        {
            foreach ($mollie->methods->all() as $method)
            {
                $methods .=  '<label ' . $this->dmm_recurring_method($method->id) . '><input type="radio" name="dmm_method" value="' . $method->id . '" ' . ($first ? 'checked' : '') . '> <img style="vertical-align:middle;display:inline-block" src="' . esc_url($method->image->normal) . '"> ' . esc_html__($method->description, 'doneren-met-mollie') . '<br></label>';
                $first = false;
            }
        }
        elseif ($option == 'list_no_icons')
        {
            foreach ($mollie->methods->all() as $method)
            {
                $methods .=  '<label ' . $this->dmm_recurring_method($method->id) . '><input type="radio" name="dmm_method" value="' . $method->id . '" ' . ($first ? 'checked' : '') . '> ' . esc_html__($method->description, 'doneren-met-mollie') . '<br></label>';
                $first = false;
            }
        }
        elseif ($option == 'list_icons')
        {
            foreach ($mollie->methods->all() as $method)
            {
                $methods .=  '<label ' . $this->dmm_recurring_method($method->id) . '><input type="radio" name="dmm_method" value="' . $method->id . '" ' . ($first ? 'checked' : '') . '> <img style="vertical-align:middle;display:inline-block" src="' . esc_url($method->image->normal) . '"></label> ';
                $first = false;
            }
        }
        elseif ($option == 'dropdown')
        {
            $methods .= '<select style="width: 100%" name="dmm_method" class="' . esc_attr(get_option('dmm_fields_cls')) . '">';
            $methods .= '<option value="">== ' . esc_html__('Choose a payment method', 'doneren-met-mollie') . ' ==</option>';
            foreach ($mollie->methods->all() as $method)
            {
                $methods .=  '<option ' . $this->dmm_recurring_method($method->id) . ' value="' . $method->id . '">' . esc_html__($method->description, 'doneren-met-mollie') . '</option>';
            }
            $methods .= '</select>';
        }


        return $methods;
    }

    /**
     * Recurring method
     *
     * @since 2.1.1
     * @param $id
     * @return string
     */
    private function dmm_recurring_method($id)
    {
        $recurring = array('ideal', 'mistercash', 'kbc', 'belfius', 'sofort', 'creditcard');

        return !in_array($id, $recurring) ? 'class="dmm_recurring"' : 'class="' . ($id == 'creditcard' ? 'dmm_cc' : 'dmm_dd') . '"';
    }

    /**
     * Project list
     *
     * @since 2.0.0
     * @param $selected
     * @return string
     */
    private function dmm_projects($selected = '') {
        $projects = explode(PHP_EOL, get_option('dmm_projects'));

        $projectList = '<select style="width: 100%" name="dmm_project" class="' . esc_attr(get_option('dmm_fields_cls')) . '">';
        $projectList .= '<option>' . esc_html__('General') . '</option>';
        foreach ($projects as $project)
        {
            $projectList .= '<option' . ($selected == $project ? ' selected' : '') . '>' . esc_attr($project) . '</option>';
        }
        $projectList .= '</select>';

        return $projectList;
    }

    /**
     * @param $slug
     * @return mixed
     */
    private function get_page_id_by_slug($slug)
    {
        $id = $this->wpdb->get_var("SELECT id FROM " . $this->wpdb->posts . " WHERE post_name = '" . esc_sql($slug) . "' AND post_type = 'page'");
        return $id;
    }
}