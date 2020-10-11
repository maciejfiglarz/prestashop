<div id="delivery_callendar_wrapper">
    <label id="callendar-before" style="text-align: center; width: 100%; font-weight: 600; margin: 15px 0px; color: #ee5604;">{if $isFishProduct != false && $isFishProduct != 'false'}{l s='W Twoim zamówieniu znajduje się ryba, którą dowozimy tylko w piątki'}{/if}</label>
    <div id="delivery_callendar"></div>
    <div id="delivery_timepicker">
        <select name="delivery_time" id="delivery_time"></select>
    </div>
    <label id="callendar-after"></label>
</div>

<script>
    var callendar_disabled_dates = '{$callendar_disabled_dates}';
    var callendar_days_offset = {$callendar_days_offset};
    var callendar_time_ranges = '{$callendar_time_ranges}';
    var callendar_days_limit = {$callendar_days_limit};
    var callendar_max_day_hour = '{$callendar_max_day_hour}';
    var callendar_last_day_max_day_hour = '{$callendar_last_day_max_day_hour}';
    var CarrersData = {$CarrersData|@json_encode nofilter};
    var selectedDateTime = '{$selectedDate}';
</script>