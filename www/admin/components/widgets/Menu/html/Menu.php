<div class="nav">
    <ul id="menu" >
        <li style="display: none;" class="directory"><a title="" class="exp"><span>Каталог</span></a>
            <ul class="sub">
                
                <li class="<?=Menu::ActiveModule("Categories,Category");?>"><a href="Index.php?module=Categories"><span>Категории</span></a></li>
                <li class="<?=Menu::ActiveModule("Properties,Property");?>"><a href="Index.php?module=Properties"><span>Свойства</span></a></li>
                <li class="<?=Menu::ActiveModule("Brands,Brand");?>"><a href="Index.php?module=Brands"><span>Бренды</span></a></li>
            </ul>
        </li>
        <li style="display: none;" class="order"><a title="" class="exp"><span>Заказы</span><?=Menu::CountOrder()?></a>
            <ul class="sub">
                <li class="<?=Menu::ActiveModule("Orders,Order");?>"><a href="Index.php?module=Orders&Status=1"><span>Все заказы</span><?=Menu::CountOrder()?></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("ReportAvailability");?>"><a href="Index.php?module=ReportAvailability" title=""><span>Заявки на товар</span><?=Menu::CountReportAvailability()?></a></li>
                <li class="<?=Menu::ActiveModule("Coupons,Coupon");?>"><a href="Index.php?module=Coupons" title=""><span>Купоны</span></a></li>
                <li class="<?=Menu::ActiveModule("Statistics");?>"><a href="Index.php?module=Statistics"><span>Статистика</span></a></li>
            </ul>
        </li>
        <li style="display: none;" class="user"><a title="" class="exp"><span>Пользователи</span><?=Menu::CountUser()?></a>
            <ul class="sub">
                <li class="<?=Menu::ActiveModule("Users,User");?>"><a href="Index.php?module=Users"><span>Все пользователи</span><?=Menu::CountUser()?></a></li>
                <li class="<?=Menu::ActiveModule("Distribution,Distributions");?>"><a href="Index.php?module=Distributions"><span>Рассылка</span></a></li>
            </ul>
        </li>
        <li style="display: none;" class="comm"><a title="" class="exp"><span>Комментарии</span><?=Menu::CountComments()?></a>
            <ul class="sub">
                <li class="<?=Menu::ActiveModule("Comments");?>"><a href="Index.php?module=Comments"><span>Все комментарии</span><?=Menu::CountComments()?></a></li>
                <li class="<?=Menu::ActiveModule("Reviews,Review");?>"><a href="Index.php?module=Reviews"><span>Отзывы</span><?=Menu::CountReviews()?></a></li>
                
                
            </ul>
        </li>
        <li class="content"><a title="" class="exp"><span>Ruswater</span></a>
            <ul class="sub">
                <li class="<?=Menu::ActiveModule("IndexContent");?>"><a href="Index.php?module=IndexContent"><span>Главная</span></a></li>
                <li class="<?=Menu::ActiveModule("TopMenus,TopMenu");?>"><a href="Index.php?module=TopMenus"><span>Верхнее меню</span></a></li>
                <li class="<?=Menu::ActiveModule("Blogs,Blog");?>"><a href="Index.php?module=Blogs"><span>Новости</span></a></li>
                <li class="<?=Menu::ActiveModule("Articles,Article");?>"><a href="Index.php?module=Articles"><span>Статьи</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("ArticlesMenu,ArticleMenu");?>"><a href="Index.php?module=ArticlesMenu"><span>Меню статей</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("Faqs,Faq");?>"><a href="Index.php?module=Faqs"><span>Вопрос-Ответ</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("OtherPages,OtherPage");?>"><a href="Index.php?module=OtherPages"><span>Другие страницы</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("CallBack");?>"><a href="Index.php?module=CallBack"><span>Заявки</span><?=Menu::CountCallBack()?></a></li>
                <li class="<?=Menu::ActiveModule("Messages");?>"><a href="Index.php?module=Messages"><span>Заявки на подбор</span><?=Menu::CountFeedBack()?></a></li>
                <li class="<?=Menu::ActiveModule("Banners,Banner");?>"><a href="Index.php?module=Banners"><span>Баннеры</span></a></li>
                <li class="<?=Menu::ActiveModule("Portfolio");?>"><a href="Index.php?module=Portfolio"><span>Портфолио</span></a></li>
                <li class="<?=Menu::ActiveModule("Forms,Form");?>"><a href="Index.php?module=Forms"><span>Конструктор</span></a></li>
                <li class="<?=Menu::ActiveModule("Products,Product");?>"><a href="Index.php?module=Products"><span>Товары</span></a></li>
                <li class="<?=Menu::ActiveModule("Settings,Page404,Colors,OrderStatus,OrderForm,Group,Letters,Letter,Moderators,Moderator,SettingsModule");?>"><a href="Index.php?module=Settings"><span>Общие</span></a></li>
              <li class="<?=Menu::ActiveModule("Calc")?>"><a href="Index.php?module=Calc"><span>Калькулятор</span></a></li>

            </ul>
        </li>
        <li style="display: none;" class="plugin"><a title="" class="exp"><span>Плагины</span></a>
            <ul class="sub">
                <li class="<?=Menu::ActiveModule("Gallery");?>"><a href="Index.php?module=Gallery"><span>Галерея</span></a></li>
                
            </ul>
        </li>
        <li style="display: none;" class="settings"><a title="" class="exp"><span>Настройки</span></a>
            <ul class="sub">
                
                <li style="display: none;" class="<?=Menu::ActiveModule("Currency");?>"><a href="Index.php?module=Currency"><span>Валюта</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("Deliveries,Delivery");?>"><a href="Index.php?module=Deliveries"><span>Доставка</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("Payments,Payment");?>"><a href="Index.php?module=Payments"><span>Оплата</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("Export,Import,YandexMarket");?>"><a href="Index.php?module=Export"><span>Автоматизация</span></a></li>
                <li style="display: none;" class="<?=Menu::ActiveModule("Structure");?>"><a href="Index.php?module=Structure"><span>Виджеты</span></a></li>
            </ul>
        </li>
    </ul>
</div>
