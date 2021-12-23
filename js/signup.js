function submit() {
    const data = {
        FirstName: document.getElementById('cust-firstname').value,
        LastName: document.getElementById('cust-lastname').value,
        Password: document.getElementById('cust-password').value,
        Email: document.getElementById('cust-email').value,
        Company: document.getElementById('cust-company').value,
        Address: document.getElementById('cust-address').value,
        City: document.getElementById('cust-city').value,
        State: document.getElementById('cust-state').value,
        Country: document.getElementById('cust-country').value,
        PostalCode: document.getElementById('cust-postal').value,
        Phone: document.getElementById('cust-phone').value,
        Fax: document.getElementById('cust-fax').value,
    }
    post('/signup', data)
        .then(() => {
            alert("Account successfully created.");
            document.location.href = '/';
        })
        .catch(() => alert("There was an error creating your account"));
}

window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('btn-signup').addEventListener('click', submit);
});