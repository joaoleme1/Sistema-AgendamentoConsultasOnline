<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendador de Consultas</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Agenda de Consultas</h1>
        <div id="calendar"></div>

        <!-- Modal de Agendamento -->
        <div class="modal fade" 
            id="modalAgendamento" 
            tabindex="-1" role="dialog" 
            aria-labelledby="exampleModalLabel" 
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agendar Consulta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-agendamento" action="processa_agendamento.php" method="POST">
                            <input type="hidden" id="data_consulta" name="data_consulta">

                            <div class="form-group">
                                <label for="nome">Nome:</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="telefone">Telefone:</label>
                                <input type="tel" class="form-control" id="telefone" name="telefone">
                            </div>
                            <div class="form-group">
                                <label for="hora_consulta">Hora:</label>
                                <input type="time" class="form-control" id="hora_consulta" name="hora_consulta" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Confirmar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error("Elemento #calendar não encontrado.");
            return;
        }

        const dataConsulta = document.getElementById('data_consulta');
        if (!dataConsulta) {
            console.error("Campo hidden #data_consulta não encontrado.");
            return;
        }

        try {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'Consulta Marcada',
                        start: '2023-10-15T10:00:00',
                        end: '2023-10-15T11:00:00'
                    }
                ],
                dateClick: function (info) {
                    console.log("Data clicada:", info.dateStr);

                    // Preencher o campo de data
                    dataConsulta.value = info.dateStr;

                    // Abrir modal
                    const modal = document.getElementById('modalAgendamento');
                    if (!modal) {
                        console.error("Modal #modalAgendamento não encontrado.");
                        return;
                    }

                    const bootstrapModal = new bootstrap.Modal(modal);
                    bootstrapModal.show();
                }
            });

            calendar.render();
            console.log("FullCalendar inicializado");
        } catch (error) {
            console.error("Erro ao inicializar o FullCalendar:", error);
        }
    });
    </script>
</body>
</html>