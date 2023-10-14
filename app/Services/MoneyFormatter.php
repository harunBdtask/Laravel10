<?php

namespace App\Services;

use App\Exceptions\InvalidAmountException;

class MoneyFormatter
{
    private $words = [
        "", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty", "Twenty-one", "Twenty-two",
        "Twenty-three", " Twenty-four", "Twenty-five", " Twenty-six", "Twenty-seven", "Twenty-eight", "Twenty-nine",
        "Thirty", "Thirty-one", "Thirty-two", "Thirty-three", "Thirty-four", "Thirty-five", "Thirty-six",
        "Thirty-seven", "Thirty-eight", "Thirty-nine", "Forty", "Forty-one", "Forty-two", "Forty-three", "Forty-four",
        "Forty-five", "Forty-six", "Forty-seven", "Forty-eight", "Forty-nine", "Fifty", "Fifty-one", "Fifty-two",
        "Fifty-three", "Fifty-four", "Fifty-five", "Fifty-six", "Fifty-seven", "Fifty-eight", "Fifty-nine", "Sixty",
        "Sixty-one", "Sixty-two", "Sixty-three", "Sixty-four", "Sixty-five", "Sixty-six", "Sixty-seven", "Sixty-eight",
        "Sixty-nine", "Seventy", "Seventy-one", "Seventy-two", "Seventy-three", "Seventy-four", "Seventy-five",
        "Seventy-six", " Seventy-seven", "Seventy-eight", "Seventy-nine", "Eighty", "Eighty-one", "Eighty-two",
        "Eighty-three", "Eighty-four", "Eighty-five", "Eighty-six", "Eighty-seven", "Eighty-eight", "Eighty-nine",
        "Ninety", "Ninety-one", "Ninety-two", "Ninety-three", "Ninety-four", "Ninety-five", "Ninety-six",
        "Ninety-seven", "Ninety-eight", "Ninety-nine"
    ];

    private $nums = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];

    private $numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    /**
     * @param $number
     * @return void
     * @throws InvalidAmountException
     */
    public function isValid($number)
    {
        if (!is_numeric($number)) {
            throw new InvalidAmountException('Non Numeric');
        }

        if ($number > 999999999999999 || strpos($number, 'E') !== false) {
            throw new InvalidAmountException('');
        }
    }

    /**
     * @param $number
     * @return string
     * @throws InvalidAmountException
     */
    public function enNum($number): string
    {
        $this->isValid($number);

        return strtr($number, $this->numbers);
    }

    /**
     * @param $number
     * @return string
     * @throws InvalidAmountException
     */
    public function enWord($number): string
    {

        $this->isValid($number);

        if ($number == 0) {
            return 'Zero';
        }

        if (is_float($number)) {
            $decimal = explode(".", $number);
            $text = $this->toWord($decimal[0]);
            if (isset($decimal[1])) {
                $text .= ' Point ' . $this->toDecimalWord((string)$decimal[1]);
            }
            return $text;
        } else {
            return $this->toWord($number);
        }
    }


    /**
     * @param $number
     * @return string
     * @throws InvalidAmountException
     */
    public function enMoney($number): string
    {
        $this->isValid($number);

        if ($number == 0) {
            return 'Zero Taka';
        }

        if (is_float($number)) {
            $money = number_format((float)$number, 2, '.', '');
            $decimal = explode(".", $money);
            $text = $this->toWord($decimal[0]) . ' Taka ';
            if (isset($decimal[1]) && $decimal[1] > 0) {
                $text .= $this->words[(int)$decimal[1]] . ' Poisa';
            }
            return $text;
        } else {
            return $this->toWord($number) . ' Taka ';
        }
    }

    /**
     * @param $number
     * @return string
     * @throws InvalidAmountException
     */
    protected function enCommaLakh($number): string
    {
        $this->isValid($number);
        $n = preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $number);
        return strtr($n, $this->numbers);
    }

    /**
     * @param $num
     * @return string
     * @throws InvalidAmountException
     */
    protected function toWord($num): string
    {

        $text = '';

        $crore = (int)($num / 10000000);
        if ($crore != 0) {
            if ($crore > 99) {
                $text .= $this->enWord($crore) . ' Crore ';
            } else {
                $text .= $this->words[$crore] . ' Crore ';
            }
        }


        $crore_div = $num % 10000000;

        $lakh = (int)($crore_div / 100000);
        if ($lakh > 0) {
            $text .= $this->words[$lakh] . ' Lakh ';
        }

        $lakh_div = $crore_div % 100000;

        $thousand = (int)($lakh_div / 1000);
        if ($thousand > 0) {
            $text .= $this->words[$thousand] . ' Thousand ';
        }

        $thousand_div = $lakh_div % 1000;

        $hundred = (int)($thousand_div / 100);
        if ($hundred > 0) {
            $text .= $this->words[$hundred] . ' Hundred ';
        }

        $hundred_div = (int)($thousand_div % 100);
        if ($hundred_div > 0) {
            $text .= $this->words[$hundred_div];
        }

        return $text;

    }

    protected function toDecimalWord($num): string
    {
        $text = '';
        $decimals = str_split($num);
        foreach ($decimals as $key => $decimal) {
            $text .= $this->nums[$decimal] . ' ';
        }
        return $text;
    }


}
