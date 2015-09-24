<div class="content">
    <table>
        <tr>
            <td>
                <h3>Hi, {NAME}</h3>
                <p class="lead">{DESCRIPTION_1}.</p>
                <p class="lead">{DESCRIPTION_2}.</p>
            </td>
        </tr>
        <tr>
            <td>
                <?php $this->load->view(EMAIL_TEMPLATES_INCLUDES_PATH . 'social_contact'); ?>
            </td>
        </tr>
    </table>
</div>
