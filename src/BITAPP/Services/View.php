<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\Core\Response;

class View
{
    /** @noinspection PhpUnusedParameterInspection */
    /**
     * @param string $name
     * @param array $params
     * @param array $errors
     * @return string
     * @throws \RuntimeException
     */
    public static function template(string $name, array $params = [], array $errors = []) : string
    {
        $template = TEMPLATE_DIR . '/' . $name . '.php';
        if (!file_exists($template)) {
            throw new \RuntimeException('Template "' . $template . '" not found.');
        }

        foreach ($errors as $field => $value) {
            Response::setError($field, $value);
        }

        ob_start();
        ob_implicit_flush(0);
        require TEMPLATE_DIR . '/layout/header.php';
        /** @noinspection PhpIncludeInspection */
        require $template;
        require TEMPLATE_DIR . '/layout/footer.php';
        return ob_get_clean();
    }
}
