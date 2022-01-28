<?php

namespace App\Application\UseCase\Fichar;

use App\Application\UseCase\Fichar\Exception\InvalidCheckInCodeException;
use App\Infrastructure\User\UserReaderRepositoryInterface;
use App\Domain\Sites\SiteOptions;
use App\Infrastructure\Utility\DateMapper;
use App\Infrastructure\Utility\MyCrypt;

class CheckInUseCase
{
    private UserReaderRepositoryInterface $userReaderRepository;

    public function __construct(UserReaderRepositoryInterface $userReaderRepository)
    {
        $this->userReaderRepository = $userReaderRepository;
    }

    public function execute(string $qrCode, SiteOptions $siteOptions): array
    {
        $decriptedToken = MyCrypt::decrypt($siteOptions->cryptKey(), $qrCode, $siteOptions->dailyQr());
        if (!$decriptedToken || !is_int($decriptedToken)) {
            throw new InvalidCheckInCodeException();
        }
        
        $user = $this->userReaderRepository->getUserById($decriptedToken);
        
        if ($user === null) {
            throw new InvalidCheckInCodeException();
        }

        $timeoutFicharSalida = 15;
        $id = $user->id();
        $nombre = $user->name();
        $activo =  $user->active();
        $sustituido =  $user->sustituted();

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $dia = DateMapper::get();
        $horaSalida = $this->userReaderRepository->getCheckOutTime($id);

        $ficharResponse = [];

        if ($activo != 1) {
            return [
                'alert' => 'error',
                'msg' => "Ha intentado Fichar estando desactivado.",
                'trigger' => false
            ];
        }

        if ($sustituido != 0) {
            return [
                'alert' => 'error',
                'msg' => "Ha intentado Fichar estando sustituido/a.",
                'trigger' => false
            ];
        }

        $sql = "SELECT Hora FROM Horas WHERE Fin >= '$hora' LIMIT 1";
        $response = $this->autocommitOffQuery($conex, $sql, $errorMsg);
        $hf = $response->fetch_assoc();
        $horaFichaje = $hf['Hora'];

        $sql = "SELECT ID, F_entrada,
            date_add(F_entrada ,interval $timeoutFicharSalida minute) ficharSalida,
            TIMEDIFF(date_add(F_entrada ,interval $timeoutFicharSalida minute), CURRENT_TIME()) countDown,
            IF(TIMEDIFF(date_add(F_entrada ,interval $timeoutFicharSalida minute), CURRENT_TIME()) < '00:00:00', 1, 0) allowFicharSalida,
            F_Salida
        FROM Fichar
        WHERE Fecha = '$fecha' AND ID_PROFESOR = '$id'";
        $response = $this->autocommitOffQuery($conex, $sql, $errorMsg);

        if ($response->num_rows == 0) {
            $sql = "INSERT INTO Fichar (ID_PROFESOR, F_entrada, F_Salida, DIA_SEMANA, Fecha) 
                        VALUES ($id, '$hora', '$horaSalida', '$dia[weekday]', '$fecha')";
            $this->autocommitOffQuery($conex, $sql, $errorMsg);
            $ficharResponse = [
                'alert' => 'success',
                'msg' => "Fichaje de asistencia correcto.<br/>$nombre",
                'trigger' => 'getGuardias'
            ];

            if ($horaFichaje === null) {
                return $ficharResponse;
            }
            $sql = "UPDATE Marcajes SET Asiste = 1 WHERE Fecha='$fecha' AND ID_PROFESOR='$id' AND Hora >= '$horaFichaje'";
            $this->autocommitOffQuery($conex, $sql, $errorMsg);

            return $ficharResponse;
        }

        if ($activeFicharSalida != 1) {
            return $ficharResponse = [
                'alert' => 'warning',
                'msg' => "Ya has fichado hoy.<br/>$nombre"
            ];
        }

        $fichaje = $response->fetch_assoc();

        if (!$fichaje['allowFicharSalida']) {
            return $ficharResponse = [
                'alert' => 'warning',
                'msg' => "Para fichar salida debe esperar al menos $timeoutFicharSalida minutos desde la entrada,<br/>Minutos restantes: $fichaje[countDown]"
            ];
        }
        if ($fichaje['F_Salida'] === $horaSalida) {
            $sql = "UPDATE Fichar SET F_Salida = '$hora' WHERE ID = '$fichaje[ID]'";
            $this->autocommitOffQuery($conex, $sql, $errorMsg);
            $sql = "UPDATE Marcajes SET Asiste='0' WHERE Fecha='$fecha' AND ID_PROFESOR='$id' AND Hora>'$horaFichaje'";
            $this->autocommitOffQuery($conex, $sql, $errorMsg);
            return $ficharResponse = [
                'alert' => 'success',
                'msg' => "Fichaje de salida correcto.<br/>$nombre",
                'trigger' => 'getGuardias'
            ];
        }

        return $ficharResponse = [
            'alert' => 'warning',
            'msg' => "Ya has fichado salida hoy.<br/>$nombre"
        ];
    }
}
