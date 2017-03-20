# Twig plugin
Добавляет Diafan.CMS поддержку шаблонизатора [Twig](http://twig.sensiolabs.org/)

## Установка
Распакуйте архив в корень вашего сайта или в папку текущей темы /custom/theme_name/

### Использование
Как создавать шаблоны модулей для Diafan.CMS [читайте в документации](https://www.diafan.ru/dokument/full-manual/developers/architecture/module/view/), а мы рассмотрим пример создания шаблона блока новости.

Шаблоны располагаются в папке *modules/имя модуля/twigs* и имеют название *modules/модуль/twigs/модуль.twig.шаблон.php*

#### /modules/news/twigs/news.twig.show_block.php
```HTML
<section class="block_news">
  <h3>{{ name }}</h3>
  <div class="block">
  {% for row in rows %}
    <a href="{{ constant('BASE_PATH_HREF') ~ row.link }}" class="item">
    {% if row.img %}
      {% set img = row.img[0] %}
      <span class="img">
        <img src="{{ img.src }}" alt="{{ img.alt }}">
      </span>
    {% endif %}
      <span class="info">
        <span class="title">{{ row.name|raw }}</span>
        <span class="date">{{ row.date|raw }}</span>
      </span>
    </a>
 {% endfor %}
 </div>
</section>
```
