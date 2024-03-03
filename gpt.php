<div class="forms">
    <form action="" method="POST">
        <label for="day">День рождения пользователя </label>
        <input id="day" name="day" type="number" value="10" max="31">
        <label for="month">Месяц рождения пользователя</label>
        <input id="month" name="month" type="number" value="5" max="12">
        <label for="year">Год рождения пользователя</label>
        <input id="year" name="year" type="number" value="1990" max="2024">
        <label for="hour">Час рождения пользователя</label>
        <input id="hour" name="hour" type="number" value="19" max="23"> 
        <label for="min">Минута рождения пользователя</label>
        <input id="min" name="min" type="number" value="55" max="59"> 
        <label for="lat">Lat</label>
        <input id="lat" name="lat" type="text" value="19.2">
        <label for="lon">Lon</label>
        <input id="lon" name="lon" type="text" value="25.2">
        <label for="tzone">Time zone</label>
        <input id="tzone" name="tzone" type="text" value="5.5">    
        <input name="astro" type="submit" value="Отправить">
    </form>
    <form action="" method="POST">
    <label for="day">День рождения</label>
        <input id="day" name="day" type="number" value="10" max="31">
        <label for="month">Месяц рождения</label>
        <input id="month" name="month" type="number" value="5" max="12">
        <label for="year">Год рождения</label>
        <input id="year" name="year" type="number" value="1990" max="2024">
        <label for="hour">Час рождения</label>
        <input id="hour" name="hour" type="number" value="19" max="23"> 
        <label for="min">Минута рождения</label>
        <input id="min" name="min" type="number" value="55" max="59"> 
        <label for="lat">Lat</label>
        <input id="lat" name="lat" type="text" value="19.2">
        <label for="lon">Lon</label>
        <input id="lon" name="lon" type="text" value="25.2">
        <label for="tzone">Time zone</label>
        <input id="tzone" name="tzone" type="text" value="5.5"> 
        <br>
        <br>
        <label for="max-tokens">Количество токенов</label>
        <input id="max-tokens" name="maxtokens" type="number" value="20" max="1000">
        <label for="promt">Промт</label>
        <textarea name="promt" id="promt" cols="30" rows="10">Что такое натальная карта</textarea>
        <input name="gpt" type="submit" value="Отправить">
    </form>    
</div>

<style>
    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 50%;
    }
    .forms {
        display: flex;
        gap: 10px;
    }
</style>
<?php
if (isset($_POST['gpt'])) {

    $url = 'https://json.astrologyapi.com/v1/astro_details';
    $data = array(
        'day' => $_POST['day'],
        'month' => $_POST['month'],
        'year' => $_POST['year'],
        'hour' => $_POST['hour'],
        'min' => $_POST['min'],
        'lat' => $_POST['lat'],
        'lon' => $_POST['lon'],
        'tzone' => $_POST['tzone'],
    );
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  // Метод запроса
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); // Тело запроса
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Возврат результата в переменную
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', // Тип содержимого запроса
        'Content-Length: ' . strlen($data_string), // Длина тела запроса
        'Authorization: Basic ' . base64_encode("628426:1fba71b00032cd2ab77287ab216ed8cc") // Basic Authentication
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    $astro = json_decode($result, true);
    $promt = (string)$_POST['promt'];
    foreach ($astro as $key => $value) 
        $promt = str_replace('[+'. $key. '+]', $value, $promt);

    $url = 'https://api.openai.com/v1/chat/completions';
    $data = array(
        'model' => 'gpt-4-turbo-preview',
        //'name' => 'asst_xMxxRc7IrNhbG954kdXQNX2N',
        //'assistant_id' => 'asst_xMxxRc7IrNhbG954kdXQNX2N',
        'messages' => array(
            array(
                'role' => 'user',
                'content' => $promt,
            ),     
        ),
        'max_tokens' => (int)$_POST['maxtokens'],
    );
    $data_string = json_encode($data);
    $token = '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';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  // Метод запроса
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); // Тело запроса
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Возврат результата в переменную
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', // Тип содержимого запроса
        'Content-Length: ' . strlen($data_string), // Длина тела запроса
        'Authorization: Bearer ' . $token, // Bearer Authentication
        //'OpenAI-Beta: assistants=v1',
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($result, true);
    //echo $result;
    //var_dump($output['choices']);
    //echo var_dump($result);
    
    echo '<label>Промт</label>';
    echo '<blockquote>';
    echo $promt;
    echo '</blockquote>';    
    echo '<label>Ответ</label>';
    echo '<blockquote>';
    echo $output['choices'][0]['message']['content'];
    echo '</blockquote>';
    
} else if (isset($_POST['astro'])) {
    $url = 'https://json.astrologyapi.com/v1/astro_details';
    $data = array(
        'day' => $_POST['day'],
        'month' => $_POST['month'],
        'year' => $_POST['year'],
        'hour' => $_POST['hour'],
        'min' => $_POST['min'],
        'lat' => $_POST['lat'],
        'lon' => $_POST['lon'],
        'tzone' => $_POST['tzone'],
    );
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  // Метод запроса
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); // Тело запроса
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Возврат результата в переменную
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json', // Тип содержимого запроса
        'Content-Length: ' . strlen($data_string), // Длина тела запроса
        'Authorization: Basic ' . base64_encode("628426:1fba71b00032cd2ab77287ab216ed8cc") // Basic Authentication
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    echo '<pre>';
    print_r(json_decode($result, true));
    echo '</pre>';
}

?>