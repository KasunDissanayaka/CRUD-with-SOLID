document.addEventListener('DOMContentLoaded', () => {
    const dbName = "customerDB";
    let db;

    const request = indexedDB.open(dbName, 1);

    request.onerror = (event) => {
        console.error('Database error:', event.target.error);
    };

    request.onsuccess = (event) => {
        db = event.target.result;
        displayData();
    };

    request.onupgradeneeded = (event) => {
        db = event.target.result;
        const objectStore = db.createObjectStore("personalData", { keyPath: "id", autoIncrement: true });
        objectStore.createIndex("name", "name", { unique: false });
        objectStore.createIndex("email", "email", { unique: true });
        objectStore.createIndex("age", "age", { unique: false });
    };

    const form = document.getElementById('customerForm');
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const newRecord = {
            name: form.name.value,
            email: form.email.value,
            age: form.age.value,
        };

        const transaction = db.transaction(["personalData"], "readwrite");
        const objectStore = transaction.objectStore("personalData");

        const request = objectStore.add(newRecord);

        request.onsuccess = () => {
            console.log('Customer added successfully');
            form.reset();
            displayData();
        };

        request.onerror = (event) => {
            console.error('Error adding customer:', event.target.error);
        };
    });

    function displayData() {
        const transaction = db.transaction(["personalData"], "readonly");
        const objectStore = transaction.objectStore("personalData");

        const request = objectStore.getAll();

        request.onsuccess = (event) => {
            const listCustomer = document.getElementById('listCustomer');
            listCustomer.innerHTML = '';
            event.target.result.forEach((record) => {
                const row = listCustomer.insertRow();
                row.insertCell(0).textContent = record.id;
                row.insertCell(1).textContent = record.name;
                row.insertCell(2).textContent = record.email;
                row.insertCell(3).textContent = record.age;
            });
        };
    }
});
