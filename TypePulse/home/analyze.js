document.addEventListener('DOMContentLoaded', function () {
    // Fetching the data from the PHP script (fetchAllData.php)
    fetch('fetchAllData.php')  // This will work when you're using http://localhost
    .then(response => response.json())  // Parse the JSON response
    .then(data => {
        // Check if the response has data
        if (data.length === 0) {
            alert("No data found.");
            return;
        }

        const tableBody = document.querySelector('#data-table tbody');
        const labels = [];
        const wpmData = [];
        const cpmData = [];
        const mistakesData = [];

        // Populate the table and prepare data for the chart
        data.forEach(item => {
            // Create a new row in the table for each data entry
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.timestamp}</td>  <!-- Using timestamp to display the date/time -->
                <td>${item.wpm}</td>
                <td>${item.cpm}</td>
                <td>${item.mistakes}</td>
            `;
            tableBody.appendChild(row);

            // Prepare data for the graph
            labels.push(item.timestamp);  // Label is the timestamp
            wpmData.push(item.wpm);
            cpmData.push(item.cpm);
            mistakesData.push(item.mistakes);
        });

        // Create the chart using Chart.js
        const ctx = document.getElementById('typingGraph').getContext('2d');
        const typingGraph = new Chart(ctx, {
            type: 'line',  // You can change this to 'bar' or other chart types if needed
            data: {
                labels: labels,  // Date values on the X-axis
                datasets: [
                    {
                        label: 'WPM',
                        data: wpmData,
                        borderColor: '#4CAF50', // Green color for WPM
                        fill: false,
                        borderWidth: 2
                    },
                    {
                        label: 'CPM',
                        data: cpmData,
                        borderColor: '#2196F3', // Blue color for CPM
                        fill: false,
                        borderWidth: 2
                    },
                    {
                        label: 'Mistakes',
                        data: mistakesData,
                        borderColor: '#F44336', // Red color for Mistakes
                        fill: false,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });

    // Event listener for the back button
    document.getElementById('back-btn').addEventListener('click', function () {
        window.location.href = 'index.html'; // Modify this as needed to redirect to your home page
    });
});
