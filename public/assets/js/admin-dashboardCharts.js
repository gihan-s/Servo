const ctx = document.getElementById('NoOfSoftwaresChart').getContext('2d');
    
new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        "2024-11-01", "2024-11-02", "2024-11-03", "2024-11-04", "2024-11-05",
        "2024-11-06", "2024-11-07", "2024-11-08", "2024-11-09", "2024-11-10",
        "2024-11-11", "2024-11-12",
      ],
      datasets: [{
        label: 'Active Posts',
        data: [89, 256, 421, 1428, 1567, 1940, 2786,2330,  3170, 3721, 4012, 4395, 4501],
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 2,
        tension: 0.4,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: {
            color: 'black',
            font: {
              weight: 'bold',
            }
          }
        },
        tooltip: {
          titleFont: {
            weight: 'bold',
          },
          bodyFont: {
            weight: 'bold',
          }
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: 'Date',
            color: 'black',
            font: {
              weight: 'bold',
            }
          },
          ticks: {
            color: 'black',
            font: {
              weight: 'bold',
            }
          }
        },
        y: {
          title: {
            display: true,
            text: 'Active Posts',
            color: 'black',
            font: {
              weight: 'bold',
            }
          },
          ticks: {
            color: 'black',
            font: {
              weight: 'bold',
            },
            beginAtZero: true,
          }
        }
      }
    }
  });



  const ctx2 = document.getElementById('VisitsSummery').getContext('2d');

  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Pending', 'Ongoing', 'Completed', 'Rejected'],
      datasets: [{
        data: [1250, 520, 1260, 80],
        backgroundColor: ['#fdb614', '#21aaffff', '#03c04a', '#FF0000'],
        hoverBackgroundColor: ['#fdb614', '#21aaffff', '#03c04a', '#FF0000']
      }]
    },
    options: {
      plugins: {
        legend: {
          display: false // Disables the legend
        },
        tooltip: {
        //   enabled: false // Optional: Disables tooltips
        }
      },
      responsive: true,
      maintainAspectRatio: false,

      cutout: '80%',
    },
    
  });