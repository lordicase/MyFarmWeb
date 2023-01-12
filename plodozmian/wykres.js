
// WYKRES

for (var i = 0; i <=5; i++) {


var label = <?php echo json_encode($label); ?>;
var temp = <?php echo json_encode($dane); ?>;

    var ctx = document.getElementById('wyk'+i);
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: label,
            datasets: [{
                label: 'Struktura',
                data: temp[i],
                backgroundColor:[
                  '#3aa47b',
                  '#0dd720',
                  '#55d011',
                  '#9af2f5',
                  '#43804d',
                  '#9192b3',
                  '#00522b',
                  '#abf600',
                  '#679d27',
                  '#2e7e08',
                  '#2e0482'
                ],

                fill:false,
                borderWidth: 1
            }]
        },
        options:{
          responsive: true,
          legend:{
            display: false,
            position: 'bottom',
          },
          title:{
            display: true,
            text: 'Struktura upraw %'
          },
          animation:{
            animateScale: true,
            animateRotate: true
          }
        }
    });
    }
