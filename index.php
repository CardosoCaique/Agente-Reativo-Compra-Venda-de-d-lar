<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<div class="container">
    <div class="col-md-12">
        <center>
        <div class="form-group" style='margin-top: 10%'>
            <form class="" action='controller.php' method="post" id='frm' onSubmit = 'sub(this); return false;'>
                <div class="col-md-6">
                    <input type='number' name='saldo' id='saldo' placeholder="Insira o saldo inicial" class="form-control">
                    <br>
                    <input style="width: 100%" type="submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function sub()
    {
        var valor = document.getElementById('saldo').value;
        console.log(valor);
        if(valor < 150) {
            alert('O valor mínimo para operação deve ser de R$ 150,00! Sugerido R$ 200,00');
            return false
        } else {
            frm.submit();
        }
    }
</script>
