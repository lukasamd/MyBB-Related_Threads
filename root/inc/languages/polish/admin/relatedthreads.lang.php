<?php
/**
 * This file is part of Related Threads plugin for MyBB.
 * Copyright (C) Lukasz Tkacz <lukasamd@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 

$l['relatedThreadsName'] = 'Powiązane tematy';
$l['relatedThreadsDesc'] = 'Ten plugin sprawdza podczas pisania tematu, czy nie istnieją już podobne wątki.';
$l['relatedThreadsGroupDesc'] = 'Ustawienia dotyczące modyfikacji "Powiązane tematy"';

$l['relatedThreadsCodeStatus'] = 'Wyświetlanie dodatkowego tekstu';
$l['relatedThreadsCodeStatusDesc'] = 'Określa, czy dodatkowy tekst nad listą tematów powiązanych ma być wyświetlany.<br />Jeżeli chcesz dodać własny kod, edytuj szablon globalny "relatedThreads_title".';

$l['relatedThreadsLength'] = 'Minimalna długość frazy';
$l['relatedThreadsLengthDesc'] = 'Określa minimalną długość tekstu dla której będą szukane powiązane tematy.';

$l['relatedThreadsLimit'] = 'Limit powiązanych tematów';
$l['relatedThreadsLimitDesc'] = 'Określa, jak wiele powiązanych tematów ma być wyświetlanych.';

$l['relatedThreadsLinkLastPost'] = 'Linki do ostatnich postów';
$l['relatedThreadsLinkLastPostDesc'] = 'Określa, czy linki mają kierować do ostatnich zamiast pierwszych postów w znalezionych tematach.';

$l['relatedThreadsNewWindow'] = 'Linki w nowym oknie';
$l['relatedThreadsNewWindowDesc'] = 'Określa, czy linki do powiązanych tematów mają być otwierane w nowym oknie / karcie.';

$l['relatedThreadsFulltext'] = 'Wyszukiwanie pełnotekstowe';
$l['relatedThreadsFulltextDesc'] = 'Określa, czy wyszukiwarka ma używać wydajniejszego systemu pełnotekstowego (o ile dostępny).';

$l['relatedThreadsExceptions'] = 'Wykluczone fora';
$l['relatedThreadsExceptionsDesc'] = 'Lista identyfikatorów for wykluczonych z wyszukiwania (oddzielone przecinkami).';

$l['relatedThreadsBadWords'] = 'Wykluczone słowa';
$l['relatedThreadsBadWordsDesc'] = 'Lista słów wykluczonych z wyszukiwania (oddzielone przecinkami).';

$l['relatedThreadsTimer'] = 'Opóźnienie w wyszukiwaniu';
$l['relatedThreadsTimerDesc'] = 'Określa czas w milisekundach od naciśniecia klawisza do rozpczęcia wyszukiwania.';

$l['relatedThreadsForumOnly'] = 'Szukaj tylko w tym samym forum';
$l['relatedThreadsForumOnlyDesc'] = 'Określa, czy podobne tematy mają być szukane tylko w tym samym forum.';

$l['relatedThreadsTimeLimitSelect'] = 'Limit czasowy (okres)';
$l['relatedThreadsTimeLimitSelectDesc'] = 'Określa okres czasowy dla sprawdzania maksymalnego wieku postów.';

$l['relatedThreadsTimeOptionNone'] = 'Brak'; 
$l['relatedThreadsTimeOptionHours'] = 'Godziny';
$l['relatedThreadsTimeOptionDays'] = 'Dni'; 
$l['relatedThreadsTimeOptionWeeks'] = 'Tygodnie'; 
$l['relatedThreadsTimeOptionMonths'] = 'Miesiące';
$l['relatedThreadsTimeOptionYears'] = 'Lata'; 
$l['relatedThreadsTimeOptionFirstPost'] = 'Pierwszy post w temacie';
$l['relatedThreadsTimeOptionLastPost'] = 'Ostatni post w temacie'; 

$l['relatedThreadsTimeLimit'] = 'Limit czasowy (wartość)';
$l['relatedThreadsTimeLimitDesc'] = 'Określa maksymalny wiek szukanych postów. Np. jeżeli wybierzesz "Dni" jako okres, oraz "3" jako wartość, szukane będą tematy mające co najwyżej 3 dni.';

$l['relatedThreadsTimeLimitMethod'] = 'Tryb limitu czasowego';
$l['relatedThreadsTimeLimitMethodDesc'] = 'Określa, dla jakiego typu postu będzie sprawdzany jego wiek.';

$l['relatedThreadsForumGet'] = 'Pobierz informacje o forum';
$l['relatedThreadsForumGetDesc'] = 'Określa, czy mają zostać pobrane informacje o dziale, w których znaleziono podobny wątek np. w celu wyświetlenia jego nazwy.';

$l['relatedThreadsShowPrefixes'] = 'Wyświetlanie prefiksów tematów';
$l['relatedThreadsShowPrefixesDesc'] = 'Jeśli włączone, obok nazwy tematu będzie wyświetlany również jego prefiks.';
