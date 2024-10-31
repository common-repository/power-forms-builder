<?php
/**
 * The admin-specific functionality of the plugin for display GDPR Complience settings.
 *
 * @link       https://www.powerformbuilder.com/
 * @since      1.0.0
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Power_Forms
 * @subpackage Power_Forms/admin
 * @author     PressTigers <support@presstigers.com>
 */
?>
<div class="fm-left" style="background: none">
    <button class="accordion pf_active"><?php esc_html_e('NOTIFICATION - IMPORTANT MESSAGE:', 'power-forms'); ?></button>
    <div class="panel" style="display: block">
        <p><?php esc_html_e('If your company does business with residents of the EU – whether or not they are paying customers and whether or not they are citizens of an EU country – or collects or processes the personal information of EU residents, collect or give out business cards, email lists, or even just tracks their web-browsing habits with cookies, it’s likely the GDPR applies.
            All U.S. businesses need to pay attention to the new and comprehensive EU-wide privacy law known as the General Data Protection Regulation (GDPR), which takes effect on May 25, 2018. With its greatly expanded compliance obligations, tough penalty regime (fines can be as much as 4% of a company’s worldwide gross revenue), and extra-territorial applicability, even businesses licensed to sell only in the U.S., and with no operations in the EU whatsoever, may nonetheless find that they are subject to the jurisdiction of GDPR.
            Continuing to service policies sold in the US to customers who later moved to the EU, for example, may raise issues regarding whether the company’s activities bring it within the jurisdiction of the GDPR, which is designed to protect the personal data of individuals in the EU, regardless of nationality.', 'power-forms'); ?></p>
    </div>

    <button class="accordion"><?php esc_html_e('What does GDPR stand for: a meaning and definition?', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('The European General Data Protection Regulation (GDPR for short) is built around two key principles.
            Giving citizens and residents more control over their personal data
            Simplifying regulations for international businesses with a unifying regulation that stands across the European Union (EU)
            It’s important to bear in mind that the GDPR will apply to any business that processes the personal data of EU citizens which means that it could also apply to companies based outside of the EU. See the GDPR checklist below for information on what ‘personal data’ includes.', 'power-forms'); ?></p>
    </div>

    <button class="accordion"><?php esc_html_e('GDPR checklist for small businesses', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('Remember, your checklist needs to take into account past and present employees and suppliers as well as customers (and anyone else’s data you’re getting hold of, storing and using', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Know your data.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('You’ll need to demonstrate an understanding of the types of personal data (for example name, address, email, bank details, photos, IP addresses) and sensitive (or special category) data (for example health details or religious views) you hold, where they’re coming from, where they’re going and how you’re using that data.', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Identify whether you’re relying on consent to process personal data.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('If you are (for example, as part of your marketing), these activities will become more difficult under the GDPR because the consent needs to be clear, specific and explicit. For this reason, you should avoid relying on consent unless absolutely necessary.', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Look hard at your security measures and policies.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('You’ll need to update these to be GDPR-compliant, and if you don’t currently have any, get them in place. Broad use of encryption could be a good way to reduce the likelihood of a big penalty in the event of a breach.', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Prepare to meet access requests within a one-month timeframe.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('Subject Access Rights are changing, and under the GDPR, citizens have the right to access all of their personal data, rectify anything that’s inaccurate and object to processing in certain circumstances, or completely erase all of their personal data that you may hold. Each request carries a timeframe and deadline of one month (which can only be extended in mitigating circumstances), from the original date of the request.', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Train your employees, and report a serious breach within 72 hours.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('Ensure your employees understand what constitutes a personal data breach and build processes to pick up any red flags. It’s also important that everybody involved in your business is aware of a need to report any mistakes to the DPO or the person or team responsible for data protection compliance, as this is the most common cause of a data breach.', 'power-forms'); ?></p>
    </div>
    <button class="accordion"><?php esc_html_e('Conduct due-diligence on your supply chain.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('You should ensure that all suppliers and contractors are GDPR-compliant to avoid being impacted by any breaches and consequent penalties. You’ll also need to ensure you have the right contract terms in place with suppliers (which puts important obligations on them, such as the need to notify you promptly if they have a data breach). See ‘How can I check my suppliers are GDPR-compliant?’ further down.', 'power-forms'); ?></p>
    </div>

    <button class="accordion"><?php esc_html_e('Create fair processing notices.', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('Under GDPR, you’re required to describe to individuals what you’re doing with their personal data. See ‘Fair processing notices’ below for more information.', 'power-forms'); ?></p>
    </div>

    <button class="accordion"><?php esc_html_e('Decide whether you need to employ a Data Protection Officer (DPO).', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('Most small businesses will be exempt. However, if your company’s core activities involve ‘regular or systematic’ monitoring of data subjects on a large scale, or which involve processing large volumes of ‘special category data’ (see ‘Is my data sensitive?’ below) you must employ a Data Protection Officer (DPO).', 'power-forms'); ?></p>
    </div>

    <button class="accordion"><?php esc_html_e('DISCLAIMER', 'power-forms'); ?></button>
    <div class="panel">
        <p><?php esc_html_e('This plugin contains general information about privacy and data protection based generally on data protection law, regulations, codes of conduct etc. However, it is not intended to provide a comprehensive or detailed statement of the law and does not constitute legal or professional advice.

            WP Power Forms use reasonable endeavors to ensure that any information contained in the plugin is correct. However, we give no representations or warranties, express or implied, in relation to the accuracy or completeness of such information. Except as expressly set out in these Terms of Use, all representations, warranties, terms, and conditions whether express or implied in relation to this website or the information contained herein are hereby excluded to the fullest extent permitted by law.', 'power-forms'); ?></p>
    </div>

</div>
<script>
    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("pf_active");
            var panel = this.nextElementSibling;
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        });
    }
</script>
