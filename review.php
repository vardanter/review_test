<?php
require_once 'db.php';

class Review
{
    private $postData;
    private $name;
    private $email;
    private $review;
    private $errors;
    private $statement;

    public function __construct()
    {
        $this->postData = $_POST;
        $this->errors = [];
        $this->init();
    }

    private function init()
    {
        $this->name = isset($this->postData['name']) ? $this->postData['name'] : '';
        $this->email = isset($this->postData['email']) ? $this->postData['email'] : '';
        $this->review = isset($this->postData['review']) ? $this->postData['review'] : '';

        try {
            if ($this->validate() && $this->save()) {
                $review = new stdClass();
                $review->user_name = $this->name;
                $review->email = $this->email;
                $review->review_text = $this->review;
                ob_start();
                require ('html/review.html.php');
                $content = ob_get_clean();
                echo json_encode([
                    'success' => true,
                    'content' => $content
                ]);
            } else {
                throw new \Exception('');
            }
        } catch(\Exception $e) {
            if (!empty($e->getMessage())) {
                $this->errors['custom'][] = $e->getMessage();
            }
            echo json_encode(['error' => $this->errors]);
        }
    }

    private function getAttributeLabel($attr)
    {
        $labels = [
            'name' => 'Имя',
            'email' => 'Эл. почта',
            'review' => 'Отзыв'
        ];

        return isset($labels[$attr]) ? $labels[$attr] : '';
    }

    private function getValidatorRules()
    {
        return [
            'name' => [
                'required' => true,
                'length' => ['min' => 1, 'max' => 255]
            ],
            'email' => [
                'required' => true,
                'filter' => 'email',
                'length' => ['max' => 255]
            ],
            'review' => [
                'required' => true,
                'length' => ['min' => 1]
            ]
        ];
    }

    private function validate()
    {
        $validatorRules = $this->getValidatorRules();

        foreach ($validatorRules as $param => $rules) {
            $label = $this->getAttributeLabel($param);
            foreach ($rules as $key => $rule) {
                switch ($key) {
                    case 'required':
                        if (empty($this->{$param})) {
                            $this->errors[$param][] = "Поле {$label} обязательно для заполнения";
                        }
                        break;
                    case 'length':
                        if (isset($rule['min']) && mb_strlen($this->{$param}, 'UTF-8') < $rule['min']) {
                            $this->errors[$param][] = "Длина значения {$label} должен быть минимум {$rule['min']} символ";
                        }
                        if (isset($rule['max']) && mb_strlen($this->{$param}, 'UTF-8') > $rule['max']) {
                            $this->errors[$param][] = "Длина значения {$label} должен быть максимум {$rule['max']} символов";
                        }
                        break;
                    case 'filter':
                        if ($rule === 'email') {
                            if (!filter_var($this->{$param}, FILTER_VALIDATE_EMAIL)) {
                                $this->errors[$param][] = "Поле {$label} содержит некоректную эл. почту";
                            }
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    private function save()
    {
        $DB = DB::getInstance();
        $sql = 'INSERT INTO reviews(user_name, email, review_text) VALUES(:userName, :email, :reviewText)';
        $DB->beginTransaction();
        try {

            $this->statement = $DB->prepare($sql);
            $this->statement->bindParam(':email', $this->email, \PDO::PARAM_STR);
            $this->statement->bindParam(':userName', $this->name, \PDO::PARAM_STR);
            $this->statement->bindParam(':reviewText', $this->review, \PDO::PARAM_STR);
            $this->statement->execute();

            $DB->commit();
        } catch (\PDOException $e) {
            $DB->rollBack();
            $this->errors['custom'][] = $e->getMessage();
        } catch (\Exception $e) {
            $DB->rollBack();
            $this->errors['custom'][] = $e->getMessage();
        }

        return empty($this->errors);
    }
}


new Review();