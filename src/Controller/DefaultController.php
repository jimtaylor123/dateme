<?php

/**
 * Centralized and useful base class :-)
 */
abstract class Base
{
    const API_VERSION = '17.04';

    const USER_NOT_FOUND = 'El usuario solicitado no existe.';
    const USER_NAME_NOT_FOUND = 'No se encontraron usuarios con ese nombre.';
    const USER_NAME_REQUIRED = 'Ingrese el nombre del usuario.';
    const USER_INFO_REQUIRED = 'Ingrese los datos a actualizar del usuario.';
    const USER_DELETED = 'El usuario fue eliminado correctamente.';

    const TASK_NOT_FOUND = 'La tarea solicitada no existe.';
    const TASK_NAME_NOT_FOUND = 'No se encontraron tareas con ese nombre.';
    const TASK_NAME_REQUIRED = 'Ingrese el nombre de la tarea.';
    const TASK_INFO_REQUIRED = 'Ingrese los datos a actualizar de la tarea.';
    const TASK_DELETED = 'La tarea fue eliminada correctamente.';

    protected $database;

    protected $request;

    protected $response;

    protected $args;

    /**
     * @param type $request
     * @param type $response
     * @param type $args
     */
    protected function setParams($request, $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    /**
     * Send response with json as standard format.
     *
     * @param type $status
     * @param type $message
     * @param type $code
     * @return array
     */
    protected function jsonResponse($status, $message, $code)
    {
        $result = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
        ];

        return $this->response->withJson($result, $code, JSON_PRETTY_PRINT);
    }

    /**
     * Response with a standard format.
     *
     * @param string $status
     * @param mixed $message
     * @param int $code
     * @return array $response
     */
    protected static function response($status, $message, $code)
    {
        $response = [
            'code'    => $code,
            'status'  => $status,
            'message' => $message,
        ];

        return $response;
    }

    /**
     * Get Help.
     *
     * @return array
     */
    public static function getHelp()
    {
        $message = ['help' => [
            'tasks' => 'Ver Tareas: /tasks',
            'users' => 'Ver Usuarios: /users',
            'version' => 'Ver Version: /version',
        ]];

        return self::response('success', $message, 200);
    }

    /**
     * Get Api Version.
     *
     * @return array
     */
    public static function getVersion()
    {
        $version = ['api_version' => self::API_VERSION];

        return self::response('success', $version, 200);
    }

    /**
     * Get Api Version.
     *
     * @return array
     */
    public function getVersionTest($request, $response, $args)
    {
        $this->setParams($request, $response, $args);
        $version = ['api_version' => self::API_VERSION];
        
        return $this->jsonResponse('success', $version, 200);
    }
}