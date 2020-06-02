<?php
/**
 * Оптимизаторский файл. Подключать только include_once!!! Не забываем global $aSEOData, где нужно.
 *
 * if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php')) {
 *   include_once($_SERVER['DOCUMENT_ROOT'] . '/d-seo.php');
 * }
 *
 * Изменяемые параметры массива $aSEOData (квадратными скобками выделены неактивные)
 * title             - title страницы
 * descr             - meta descr
 * keywr             - meta keywords
 *
 */

//Глобальные значения (по умолчанию)
  $aSEOData['title'] = '';
  $aSEOData['descr'] = '';
  $aSEOData['keywr'] = '';

//Определяем адрес (REQUEST_URI есть не всегда)
  $sSEOUrl = $_SERVER['REQUEST_URI'];


if(preg_match('#<title>([^<]+)</title>#siU', $sContent, $res))
{
  if(preg_match('#/en/#siU', DUR_REQUEST_URI))
  {
      $aSEOData['descr'] = 'Enjoy the truly authentic experiences of thai SPA and massage. '.$res[1].'. Telephone: +7 (495) 945-88-99';
  }
  else
  {
      $aSEOData['descr'] = 'Насладитесь истинным удовольствием тайского CПА и массажа. '.$res[1].'. Наш телефон в Москве: +7 (495) 945-88-99';
  }
}


//Собственно вариации для страниц
  switch ($sSEOUrl) {
    case '/url.php':
      $aSEOData['title'] = 'Тайтл успешно подменен';
      break;
    case '/': // это ЧПУ
        $aSEOData['title'] = 'Салон тайского массажа Thai Pattara Spa на западе Москвы (ЗАО)';
        $aSEOData['descr'] = 'Салон тайского массажа Thai Pattara Spa предлагает широкий выбор услуг для своих клиентов. Гарантируем индивидуальный подход к каждому клиенту, проводим промоакции. Получить консультацию специалистов можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'салон тайского массажа центр spa спа зао на беговой';
   
      break;



    case '/%D0%BC%D0%B5%D0%BD%D1%8E-%D1%81%D0%BF%D0%B0': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж от Thai Pattara Spa в Москве';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский SPA-массаж по оптимальным ценам в Москве. Гарантируем индивидуальный подход к каждому клиенту. Опытные специалисты используют в работе различные техники. Телефон: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж в москве недорого цена стоимость сколько стоит spa спа на беговой полежаевская';
       
      break;

    
    case '/%D0%BF%D0%BE%D0%B4%D0%B0%D1%80%D0%BE%D1%87%D0%BD%D1%8B%D0%B5-%D1%81%D0%B5%D1%80%D1%82%D0%B8%D1%84%D0%B8%D0%BA%D0%B0%D1%82%D1%8B': // это ЧПУ
        $aSEOData['title'] = 'Подарочные сертификаты на тайский массаж';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает сертификаты на тайский массаж по выгодным ценам в Москве. Порадовать родных и близких можно быстро и удобно с помощью подарка от нашего салона. Получить консультацию и оформить заказ можно по телефону: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж сертификат';
      
      break;

   case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D1%81%D0%BF%D0%B8%D0%BD%D1%8B':
    case '/тайский-массаж/спины': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж спины от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж спины по оптимальным ценам в Москве. Опытные специалисты проведут процедуру в удобное для клиента время. Получить консультацию и оформить заказ можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж спины';
       
      break;

   case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D0%BB%D0%B8%D1%86%D0%B0':
    case '/тайский-массаж/лица': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж лица от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж лица на выгодных условиях в Москве. Предлагаем различные техники проведения процедуры. Получить консультацию специалистов можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж лица';
        
      break;

   case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D0%BD%D0%BE%D0%B3':
    case '/тайский-массаж/ног': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж ног от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж ног по оптимальным ценам в Москве. Гарантируем индивидуальный подход к каждому клиенту, проводим промоакции. Получить консультацию специалистов можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж ног';
       
      break;

   case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D1%80%D1%83%D0%BA':
    case '/тайский-массаж/рук': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж рук от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж рук по выгодным ценам в Москве. Опытные специалисты используют в работе различные приемы и техники, проводим промоакции. Телефон: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж рук';
 
      break;


    case '/%D0%BD%D0%B0%D1%88%D0%B8-%D0%BF%D0%B0%D1%80%D1%82%D0%BD%D0%B5%D1%80%D1%8B/%D1%82%D0%B0%D0%B9%D1%81%D0%BA%D0%B8%D0%B9-%D1%81%D0%BB%D0%BE%D0%BD-%D0%BC-%C2%AB%D0%BF%D0%BE%D0%BB%D0%B5%D0%B6%D0%B0%D0%B5%D0%B2%D1%81%D0%BA%D0%B0%D1%8F%C2%BB': // это ЧПУ
        $aSEOData['title'] = 'Ресторан «Тайский слон» на Полежаевской';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa является партнером ресторана «Тайский слон»  на Хорошевском шоссе в Москве. Предлагаем разнообразного меню национальной и европейской кухни. Гарантируем индивидуальный подход к каждому клиенту.';
        $aSEOData['keywr'] = 'тайский слон на полежаевской хорошевском шоссе';

      break;

    case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D1%82%D1%80%D0%B0%D0%B2%D1%8F%D0%BD%D1%8B%D0%BC%D0%B8-%D0%BC%D0%B5%D1%88%D0%BE%D1%87%D0%BA%D0%B0%D0%BC%D0%B8':
    case '/тайский-массаж/травяными-мешочками': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж травяными мешочками от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж травяными мешочками по оптимальным ценам в Москве. Опытные специалисты проведут процедуру в удобное для клиента время. Получить консультацию и оформить заказ можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж травяными мешочками';

      break;

   case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D0%BF%D0%B0%D0%BB%D0%BE%D1%87%D0%BA%D0%B0%D0%BC%D0%B8':
    case '/тайский-массаж/палочками': // это ЧПУ
        $aSEOData['title'] = 'Тайский массаж палочками от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж палочками по выгодным ценам в Москве. Гарантируем индивидуальный подход к каждому клиенту, проводим промоакции. Получить консультацию специалистов можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж палочками';
  
      break;

    case '/%D1%80%D0%B0%D1%81%D1%81%D0%BB%D0%B0%D0%B1%D0%BB%D1%8F%D1%8E%D1%89%D0%B8%D0%B9-%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6':
    case '/тайский-массаж/расслабляющий':     // Добавлено 20 July 2015 в  9:06
        $aSEOData['title'] = 'Тайский расслабляющий массаж от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предоставляет услуги тайского расслабляющего массажа. Квалифицированные специалисты подберут индивидуальную программу для каждого клиента. Оформить заказ можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский расслабляющий массаж';
 
      break;

    case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D1%81%D1%82%D0%BE%D0%BF':
    case '/тайский-массаж/стоп':     // Добавлено 20 July 2015 в  9:06
        $aSEOData['title'] = 'Тайский массаж стоп от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж стоп по выгодным ценам в Москве. Опытные специалисты используют в работе различные приемы и техники. Для клиентов доступны промоакции. Телефон: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж стоп';
     
      break;

    case '/%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6-%D0%BC%D0%B0%D1%81%D0%BB%D0%BE%D0%BC':
    case '/тайский-массаж/маслом':     // Добавлено 20 July 2015 в  9:06
        $aSEOData['title'] = 'Тайский массаж с маслом от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский массаж с маслом по оптимальным ценам в Москве. Опытные специалисты проведут процедуру в удобное для клиента время. Получить консультацию и оформить заказ можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'тайский массаж с маслом oil';

      break;

    case '/%D0%B0%D0%BD%D1%82%D0%B8%D1%86%D0%B5%D0%BB%D0%BB%D1%8E%D0%BB%D0%B8%D1%82%D0%BD%D1%8B%D0%B9-%D0%BC%D0%B0%D1%81%D1%81%D0%B0%D0%B6':
    case '/ru/антицеллюлитный-массаж':     // Добавлено 20 July 2015 в  9:06
        $aSEOData['title'] = 'Тайский антицеллюлитный массаж от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает тайский антицеллюлитный массаж на выгодных условиях в Москве. Опытные специалисты используют различные техники проведения процедуры. Получить консультацию специалистов можно по телефону +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'антицеллюлитный массаж тайский';
  
      break;

    case '/%D1%80%D0%B0%D0%B9%D1%81%D0%BA%D0%B8%D0%B9-%D1%82%D0%B0%D0%B9-%D0%BF%D0%B0%D1%82%D1%82%D0%B0%D1%80%D0%B0': // это ЧПУ
        $aSEOData['title'] = 'Райская программа от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает райскую программу «Тай-Паттара» для обновления кожи и общего оздоровления. Процедура помогает избавиться от лишней жидкости и токсинов. Предлагаем оформить сертификат.';
        $aSEOData['keywr'] = 'Thai, Spa, Traditional, Massage, Authentic, Head, Neck, Shoulder, Hand, Foot, Back, Face, Body, Scrub, Winter, Summer, Parking, Begovaya, Moscow, Bogovoy, Russia, Thailand, Slim, Wrap, Hair, Head-to-toe, Menu, Gift, Certificate, Membercard, Product, Sauna,тайский, Спа, Традиционный, Массаж, подлинный, голова, Шея, Плечо, Рука, ног, спины, лица, Телa, Скраб, Зима, Лето, Парковка, Беговая, Москва, Россия, Таиландa, похудения, Оберните, Волос, головы до ног, Меню, Подарок, Сертификат, Карта Клиента, Продукт, Сауна,';
      break;
    case '/%D0%BF%D1%80%D0%BE%D0%B3%D1%80%D0%B0%D0%BC%D0%BC%D0%B0-%D1%81%D0%BA%D1%80%D0%B0%D0%B1-%D0%B4%D0%BB%D1%8F-%D1%82%D0%B5%D0%BB%D0%B0': // это ЧПУ
        $aSEOData['title'] = 'Программа «Скраб для тела» от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает уникальную программу «Скраб для тела» для ухода за кожей. Используем только натуральные косметические средства. Консультации по телефону: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'Thai, Spa, Traditional, Massage, Authentic, Head, Neck, Shoulder, Hand, Foot, Back, Face, Body, Scrub, Winter, Summer, Parking, Begovaya, Moscow, Bogovoy, Russia, Thailand, Slim, Wrap, Hair, Head-to-toe, Menu, Gift, Certificate, Membercard, Product, Sauna,тайский, Спа, Традиционный, Массаж, подлинный, голова, Шея, Плечо, Рука, ног, спины, лица, Телa, Скраб, Зима, Лето, Парковка, Беговая, Москва, Россия, Таиландa, похудения, Оберните, Волос, головы до ног, Меню, Подарок, Сертификат, Карта Клиента, Продукт, Сауна,';
      break;
    case '/%D0%BF%D0%BE%D1%81%D0%BB%D0%B5%D1%80%D0%BE%D0%B4%D0%BE%D0%B2%D0%B0%D1%8F-%D0%B7%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D0%B1%D1%8D%D0%B1%D0%B8-%D0%B1%D1%83%D0%BC': // это ЧПУ
        $aSEOData['title'] = 'Послеродовая забота «Бэби Бум» от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает уникальную послеродовую программу «Бэби Бум» для восстановления фигуры. Гарантируем безопасность проведения процедуры. Консультации по телефону: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'Thai, Spa, Traditional, Massage, Authentic, Head, Neck, Shoulder, Hand, Foot, Back, Face, Body, Scrub, Winter, Summer, Parking, Begovaya, Moscow, Bogovoy, Russia, Thailand, Slim, Wrap, Hair, Head-to-toe, Menu, Gift, Certificate, Membercard, Product, Sauna,тайский, Спа, Традиционный, Массаж, подлинный, голова, Шея, Плечо, Рука, ног, спины, лица, Телa, Скраб, Зима, Лето, Парковка, Беговая, Москва, Россия, Таиландa, похудения, Оберните, Волос, головы до ног, Меню, Подарок, Сертификат, Карта Клиента, Продукт, Сауна,';
      break;

    case '/%D1%80%D0%B0%D1%81%D1%81%D0%BB%D0%B0%D0%B1%D0%BB%D1%8F%D1%8E%D1%89%D0%B5%D0%B5-%D0%BB%D0%B5%D1%87%D0%B5%D0%BD%D0%B8%D0%B5-%D0%B3%D0%BE%D1%80%D1%8F%D1%87%D0%B8%D0%BC%D0%B8-%D0%BA%D0%B0%D0%BC%D0%BD%D1%8F%D0%BC%D0%B8':     // Добавлено 25 August 2015 в  17:52
        $aSEOData['title'] = 'Расслабляющее лечение горячими камнями от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает уникальное расслабляющее лечение горячими камнями. Опытные специалисты помогут снять физическое напряжение, вывести лишние жидкости из организма. Консультации по телефону: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = '';
      break;

    case '/%D1%82%D0%B0%D0%B9%D0%BD%D0%B0-%D1%82%D0%B5%D0%BB%D0%B0-%D0%B4%D0%BB%D1%8F-%D0%BF%D0%BE%D1%85%D1%83%D0%B4%D0%B5%D0%BD%D0%B8%D1%8F': // это ЧПУ
        $aSEOData['title'] = 'Программа «Тайна тела» от салона Thai Pattara Spa';
        $aSEOData['descr'] = 'Салон Thai Pattara Spa предлагает программу «Тайна тела» для похудения и улучшения контуров фигуры. Опытные специалисты подберут оптимальную систему для быстрого снижения веса. Консультации по телефону: +7 (495) 945-88-99.';
        $aSEOData['keywr'] = 'Thai, Spa, Traditional, Massage, Authentic, Head, Neck, Shoulder, Hand, Foot, Back, Face, Body, Scrub, Winter, Summer, Parking, Begovaya, Moscow, Bogovoy, Russia, Thailand, Slim, Wrap, Hair, Head-to-toe, Menu, Gift, Certificate, Membercard, Product, Sauna,тайский, Спа, Традиционный, Массаж, подлинный, голова, Шея, Плечо, Рука, ног, спины, лица, Телa, Скраб, Зима, Лето, Парковка, Беговая, Москва, Россия, Таиландa, похудения, Оберните, Волос, головы до ног, Меню, Подарок, Сертификат, Карта Клиента, Продукт, Сауна,';
      break;
  
  }  






$li = <<<html
<div class="li">
<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a rel='nofollow' href='//www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t50.6;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet' "+
"border='0' width='31' height='31'><\/a>")
//--></script><!--/LiveInternet-->
</div>
html;


$sContent = str_replace('<div class="li"></div>', $li, $sContent);
$sContent = preg_replace('#<h3 class="module">(.*)</h3>#siU', '<div class="module">$1</div>', $sContent);

if(DUR_REQUEST_URI == '/')
{
    $sContent = str_replace('<link href="http://www.thaipattaraspa.ru/" rel="canonical" />','', $sContent);
}
else if(DUR_REQUEST_URI == '/%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D1%8B')
{
$schema = '
<div itemscope itemtype="http://schema.org/Organization" style="display: none;">
  <span itemprop="name">ТАЙ ПАТТАРА СПА</span>


  <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">

      <span itemprop="streetAddress">ул. Беговая, 26</span>
      <span itemprop="postalCode">125284</span>
      <span itemprop="addressLocality">Москва</span>

  </div>
    <span itemprop="telephone">+7 (495) 945-88-99, +7 (985) 280-96-79
+7 (919) 777-34-95, +7 (910) 446-75-93</span>
    <span itemprop="email">info@thaipattaraspa.com</span>
</div>'; 

  $sContent = str_replace('</body>',$schema.'</body>', $sContent);
}









//Обработка
  function changeHeadBlock ($sContent, $sRegExp, $sBlock) {
    if (preg_match($sRegExp, $sContent)) {
      return preg_replace($sRegExp, $sBlock, $sContent);
    }
    else {
      return str_replace('<head>', '<head>' . $sBlock, $sContent);
    }
  }
  if (isset($aSEOData['title']) && !empty($aSEOData['title'])) {
    $aSEOData['title'] = htmlspecialchars($aSEOData['title']);
    $sContent = changeHeadBlock($sContent, '#<title>.*</title>#siU', '<title>' . $aSEOData['title'] . '</title>');
  }
  if (isset($aSEOData['descr']) && !empty($aSEOData['descr'])) {
    $aSEOData['descr'] = htmlspecialchars($aSEOData['descr']);
    $sContent = changeHeadBlock($sContent, '#<meta[^>]+name[^>]{1,7}description[^>]*>#siU', '<meta name="description" content="' . $aSEOData['descr'] . '" />');
  }
  if (isset($aSEOData['keywr']) && !empty($aSEOData['keywr'])) {
    $aSEOData['keywr'] = htmlspecialchars($aSEOData['keywr']);
    $sContent = changeHeadBlock($sContent, '#<meta[^>]+name[^>]{1,7}keywords[^>]*>#siU', '<meta name="keywords" content="' . $aSEOData['keywr'] . '" />');
  }

?>
