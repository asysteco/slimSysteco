if (filterAsistencias) {
    $(filterAsistencias).datepicker({
        maxDate: 0,
        beforeShowDay: $.datepicker.noWeekends,
        onSelect: function(date) {
            getAsistenciasByDate(date);
        }
    })
}

async function getAsistenciasByDate(date) {
    if (!date) {
        toastr["warning"]("Debe seleccionar una fecha para filtrar", defaultAlertTitle);
        return;
    }

    var filterData = {
        date: date
    };

    loadingOn();
    const response = await fetch(filterAsistenciasRoute, {
      method: 'POST',
      body: JSON.stringify(filterData),
      headers:{
        'Content-Type': 'application/json'
      }
    })
    .then(res => res.json())
    .then(res => {
      if (res.success && res.data) {
        let fichajesBody = document.getElementById('table-fichajes-body');
        let faltasBody = document.getElementById('table-faltas-body');
        let fichajes = res.data.fichajes;
        let faltas = res.data.faltas;

        $(fichajesBody).find("tr").remove();
        $(faltasBody).find("tr").remove();

        if (fichajes.length) {
            fichajes.forEach(function(fichaje) {
                var row = fichajesBody.insertRow();

                var name = row.insertCell();
                var checkIn = row.insertCell();
                var checkOut = row.insertCell();
                var weekDay = row.insertCell();

                var nameText = document.createTextNode(fichaje.name);
                var checkInText = document.createTextNode(fichaje.checkIn);
                var checkOutText = document.createTextNode(fichaje.checkOut);
                var weekDayText = document.createTextNode(fichaje.weekDay);

                name.appendChild(nameText);
                checkIn.appendChild(checkInText);
                checkOut.appendChild(checkOutText);
                weekDay.appendChild(weekDayText);
            });
        } else {
            var row = fichajesBody.insertRow();
            var empty = row.insertCell();
            empty.colSpan = 4;
            empty.appendChild(document.createTextNode('No existen registros de fichajes.'));
        }

        if (faltas.length) {
            faltas.forEach(function(falta) {
                var row = faltasBody.insertRow();

                var name = row.insertCell();
                var weekDay = row.insertCell();

                var nameText = document.createTextNode(falta.name);
                var weekDayText = document.createTextNode(falta.weekDay);

                name.appendChild(nameText);
                weekDay.appendChild(weekDayText);
            });
        } else {
            var row = fichajesBody.insertRow();
            var empty = row.insertCell();
            empty.colSpan = 2;
            empty.appendChild(document.createTextNode('No existen registros de falta.'));
        }
      } else {
        toastr["error"](defaultFilterMessage, defaultErrorTitle);
      }
      loadingOff();
    })
    .catch(function () {
      loadingOff();
      toastr["error"](defaultCatchMessage, defaultErrorTitle)
    });
}