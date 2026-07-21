/**
 * When a Google Form is submitted (spreadsheet), also send a detailed enquiry email.
 */
(function () {
    function collectEnquiry(form) {
        var data = { subject: 'New Enquiry from Website' };
        form.querySelectorAll('[data-enquiry-field]').forEach(function (el) {
            var key = el.getAttribute('data-enquiry-field');
            if (!key) return;
            data[key] = (el.value || '').trim();
        });
        return data;
    }

    function sendEnquiryEmail(form, data) {
        if (!data.name || !data.email || !data.message) return;

        var url = form.getAttribute('data-enquiry-email-url') || 'assets/inc/sendemail.php';
        var body = new URLSearchParams(data);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: body.toString(),
            credentials: 'same-origin'
        }).catch(function () { /* Google submit is primary; email is best-effort */ });
    }

    function bindForm(form) {
        if (!form || form.getAttribute('data-google-form') !== '1') return;
        if (form.dataset.enquiryEmailBound === '1') return;
        form.dataset.enquiryEmailBound = '1';

        form.addEventListener('submit', function () {
            sendEnquiryEmail(form, collectEnquiry(form));
        });
    }

    function init() {
        document.querySelectorAll('form[data-google-form="1"]').forEach(bindForm);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
