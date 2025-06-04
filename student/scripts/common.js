$(document).ready(function () {
  const d1 = [ [0, 1], [1, 14], [2, 5], [3, 4], [4, 5], [5, 1], [6, 14], [7, 5], [8, 5] ];
  const d2 = [ [0, 5], [1, 2], [2, 10], [3, 1], [4, 9], [5, 5], [6, 2], [7, 10], [8, 8] ];

  function initChart(target, label1, label2, fill) {
    $.plot($(target), [
      { data: d1, label: label1 },
      { data: d2, label: label2 }
    ], {
      lines: { show: true, fill, lineWidth: 2 },
      points: { show: true, lineWidth: 5 },
      grid: {
        clickable: true,
        hoverable: true,
        backgroundColor: "#fafafa",
        borderWidth: 0,
        minBorderMargin: 25,
      },
      colors: ["#090", "#099", "#609", "#900"],
      shadowSize: 0
    });
  }

  if ($('.datatable-1').length > 0) {
    $('.datatable-1').dataTable();
    $('.dataTables_paginate').addClass('btn-group datatable-pagination');
    $('.dataTables_paginate > a').wrapInner('<span />');
    $('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
    $('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');

    $('.slider-range').slider({
      range: true,
      min: 0,
      max: 20000,
      values: [3000, 12000],
      slide: function (event, ui) {
        $(this).find('.ui-slider-handle').attr('title', ui.value);
      }
    });
    $('#amount').val(`$${$('.slider-range').slider('values', 0)} - $${$('.slider-range').slider('values', 1)}`);

    initChart('#placeholder2', 'Profits', 'Expenses', true);
  } else {
    initChart('#placeholder', 'Data A', 'Data B', false);
    initChart('#placeholder2', 'Data Y', 'Data X', true);

    const pieData = [
      { label: "Series1", data: [[1, 10]] },
      { label: "Series2", data: [[1, 30]] },
      { label: "Series3", data: [[1, 90]] }
    ];

    $.plot("#pie-default", pieData, {
      series: { pie: { show: true } }
    });

    $.plot("#pie-donut", pieData, {
      series: { pie: { innerRadius: 50, show: true } }
    });

    $.plot("#pie-interactive", pieData, {
      series: { pie: { innerRadius: 50, show: true } },
      grid: { hoverable: true, clickable: true }
    });

    $("#pie-interactive").bind("plothover", function (event, pos, obj) {
      if (!obj) return;
      const percent = parseFloat(obj.series.percent).toFixed(2);
      $("#hover").html(`<span>${obj.series.label} - ${percent}%</span>`);
    });

    $("#pie-interactive").bind("plotclick", function (event, pos, obj) {
      if (!obj) return;
      const percent = parseFloat(obj.series.percent).toFixed(2);
      alert(`${obj.series.label}: ${percent}%`);
    });
  }
});
