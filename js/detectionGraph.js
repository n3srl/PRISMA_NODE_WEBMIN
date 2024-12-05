document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('graficoDetection');
    const datePicker = document.getElementById('datePicker'); 

    function creaGrafico(dataSelezionata) {
        $.ajax({
            type: "GET",
            url: "/lib/detection/V2/detection/getPrevWeeks",
            headers: {
                'Content-Type': 'application/json',
            },
            data: {
                date: dataSelezionata 
            },
            success: function (response) {
                if (!response.success) {
                    console.error("Errore:", response.message);
                    return;
                }
    
                const weeklyDetections = response.data;
    
                const labels = weeklyDetections.map(
                    week => `${week.week_start} - ${week.week_end}`
                );
                const data = weeklyDetections.map(week => week.detections);
                if (window.grafico) {
                    window.grafico.destroy();
                }
    
                //grafico
                window.grafico = new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Numero di rilevazioni',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Numero di rilevazioni'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Settimane'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                labels: {
                                    color: '#000',
                                    font: {
                                        size: 14
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Errore AJAX:", status, error);
            }
        });
    }

    creaGrafico(new Date().toISOString().split('T')[0]); 

    datePicker.addEventListener('change', function(event) {
        const selectedDate = event.target.value;
        creaGrafico(selectedDate); 
    });
});
