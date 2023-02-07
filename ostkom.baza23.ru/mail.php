<?
if (mail("yegoshin@baza23.ru","test subject", "test body","From: info@baza23.ru"))
echo "Сообщение передано функции mail, проверьте почту в ящике.";
else
echo "Функция mail не работает, свяжитесь с администрацией хостинга.";
