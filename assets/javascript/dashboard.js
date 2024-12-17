document.addEventListener('DOMContentLoaded', () => {
  // Chart.js configuration
  const ctx = document.getElementById('bookingsChart').getContext('2d');

  new Chart(ctx, {
      type: 'bar',
      data: {
          labels: monthlyLabels, // Labels for months
          datasets: [
              {
                  label: 'Bookings Per Month',
                  data: monthlyData, // Data for each month
                  backgroundColor: 'rgba(75, 192, 192, 0.6)',
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1,
              },
          ],
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  display: false,
              },
              title: {
                  display: true,
                  text: 'Monthly Bookings',
              },
          },
          scales: {
              x: {
                  title: {
                      display: true,
                      text: 'Months',
                  },
              },
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: 'Number of Bookings',
                  },
              },
          },
      },
  });
});
