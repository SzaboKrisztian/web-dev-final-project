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
    put('/customer', data)
        .then(() => {
            alert("Account successfully updated.");
            document.location.href = '/';
        })
        .catch(() => alert("There was an error updating your account"));
}

window.addEventListener('DOMContentLoaded', () => {
    get('/customer').then(data => {
        document.getElementById('cust-firstname').value = data.FirstName;
        document.getElementById('cust-lastname').value = data.LastName;
        document.getElementById('cust-email').value = data.Email;
        document.getElementById('cust-company').value = data.Company;
        document.getElementById('cust-address').value = data.Address;
        document.getElementById('cust-city').value = data.City;
        document.getElementById('cust-state').value = data.State;
        document.getElementById('cust-country').value = data.Country;
        document.getElementById('cust-postal').value = data.PostalCode;
        document.getElementById('cust-phone').value = data.Phone;
        document.getElementById('cust-fax').value = data.Fax;
    });
    document.getElementById('btn-signup').addEventListener('click', submit);
});