const chartData = window.dashboardChartData || { postActivity: { labels: [], counts: [] }, projectCounts: { pending: 0, ongoing: 0, completed: 0, rejected: 0 } };

const ctx = document.getElementById('NoOfSoftwaresChart').getContext('2d');
    
new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartData.postActivity.labels,
      datasets: [{
        label: 'Total Requests',
        data: chartData.postActivity.counts,
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
            text: 'Total Requests',
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
        data: [
          chartData.projectCounts.pending,
          chartData.projectCounts.ongoing,
          chartData.projectCounts.completed,
          chartData.projectCounts.rejected
        ],
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