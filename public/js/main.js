
    var ul = document.querySelectorAll('.treeline > li:not(:only-child) ul, .treeline ul ul');
    for (var i = 0; i < ul.length; i++) {
        var div = document.createElement('div');
        div.className = 'drop';
        div.innerHTML = '+';
        ul[i].parentNode.insertBefore(div, ul[i].previousSibling);
        div.onclick = function() {
            this.innerHTML = (this.innerHTML == '+' ? '−' : '+');
            this.className = (this.className == 'drop' ? 'drop dropM' : 'drop');
        }
    }


    $(document).on('click', '.edit', function (ev) {
        let target = ev.target;
        if ($(target.parentNode).find('.editForm')[0] != undefined) return false

        target.parentNode.innerHTML += `
                            <form class="was-validated mt-3 editForm" enctype="multipart/form-data" method="post"  action="/update/${target.dataset.messageid}" >
                                <div class="custom-file">
                                    <input type="file" name="img" class="custom-file-input" id="validatedCustomFile" required>
                                    <label class="custom-file-label" for="validatedCustomFile">Выберите картинку</label>
                                    <div class="invalid-feedback">
                                        картинка должна быть не более 500 х 500, но и не менее 100 х 100 пикселей,. Размер картинки не должен превышать 100 Кб.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Текст</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="text" required minlength="10" maxlength="1000" rows="3"></textarea>
                                </div>
                                <input type="hidden" name="messageId" value="${target.dataset.messageid}">
                                <input type="submit" value="Сахранить изменение">
                            </form>
                              `
    })

    $(document).on('submit', '.editForm', function (ev) {
        ev.preventDefault();
        let text = $(ev.target).find('[name=text]').val()
        let messageId = $(ev.target).find('[name=messageId]').val()
        let img = $('[name=img]')[0].files[0];

        let formData = new FormData();
        formData.append("img",img);
        formData.append("text",text);
        formData.append("messageId",messageId);


        $.ajax({

            url: `/update/${messageId}`,

            dataType:'json',
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            data: formData,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (data) {
                let img = $(ev.target.parentNode.parentNode).find('img').attr('src',`uploads/images/${data.img}`);
                $(ev.target.parentNode.parentNode).find('.content').text(text)
                $('.editForm').remove()

            },

            error: function (msg) {
                let outLi = ''
                $.each(msg.responseJSON.validate, function(key,value) {
                    outLi +=`<li>${value}</li>`
                })
                let out = `
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger">
                                <ul>
                                    ${outLi}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                `
                $('.errors').html('')
                $(ev.target.parentNode).find('.errors').append(out);
                console.log(ev.target.parentNode)
            }

        });
    })

    $(document).on('click', '.ToAnswer', function (ev) {

        let target = ev.target;
        target.style.display = 'none'

        target.parentNode.innerHTML += `
                            <form class="was-validated mt-3 ToAnswerForm" enctype="multipart/form-data" method="post" >
                                <div class="custom-file">
                                    <input type="file" name="img" class="custom-file-input" id="validatedCustomFile" required>
                                    <label class="custom-file-label" for="validatedCustomFile">Выберите картинку</label>
                                    <div class="invalid-feedback">
                                        картинка должна быть не более 500 х 500, но и не менее 100 х 100 пикселей,. Размер картинки не должен превышать 100 Кб.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Текст</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="text" required minlength="10" maxlength="1000" rows="3"></textarea>
                                </div>
                                <input type="hidden" name="messageId" value="${target.dataset.messageid}">
                                <input type="submit">
                            </form>
                              `
    })

    $(document).on('submit', '.ToAnswerForm', function (ev) {
        ev.preventDefault();
        let text = $(ev.target).find('[name=text]').val()
        let messageId = $(ev.target).find('[name=messageId]').val()

        let formData = new FormData();
        formData.append("img",$('[name=img]')[0].files[0]);
        formData.append("text",text);
        formData.append("messageId",messageId);
        $.ajax({

            url: '/store',

            dataType:'json',
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            data: formData,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (data) {
            console.log(data)
                ev.target.parentNode.parentNode.parentNode.innerHTML += `
        <ul>
            <li>
                <div>
                    <img src="uploads/images/${data[0]['img']}" width="150px">
                </div>
                <div>
                    <div>
                        <div contenteditable="false" class="content"> ${data[0]['content']} </div>
                    </div>
                    <div>
                        <input type="button" class="edit" value="Редактировать">
                        <input type="button" class="ToAnswer" value="Ответить" data-messageId="${data[0]['id']}">
                    </div>
                </div>
            </li>
        </ul>
        `
                $('.ToAnswerForm').parent().remove()

            },

            error: function (msg) {
                let outLi = ''
                $.each(msg.responseJSON.validate, function(key,value) {
                    outLi +=`<li>${value}</li>`
                })
                let out = `
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger">
                                <ul>
                                    ${outLi}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                `
                $('.errors').html('')
                $(ev.target.parentNode).find('.errors').append(out);
            }

        });
    });

    $('#store').on('submit', function (ev){
        ev.preventDefault();
        let text = $(ev.target).find('[name=text]').val()

        var formData = new FormData();
        formData.append("img",$('[name=img]')[0].files[0]);
        formData.append("text",text);

        $.ajax({

            url: '/store',

            dataType:'json',
            async:false,
            type:'post',
            processData: false,
            contentType: false,
            data: formData,
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (data) {
                console.log(data)
                $('.treeline')[0].innerHTML += `
                    <li>
                        <div>
                            <img src="uploads/images/${data[0]['img']}" width="150px">
                        </div>
                        <div>
                            <div>
                                <div contenteditable="false" class="content"> ${data[0]['content']} </div>
                            </div>
                            <div>
                                <input type="button" class="edit" value="Редактировать">
                                <input type="button" class="ToAnswer" value="Ответить" data-messageId="${data[0]['id']}">
                            </div>
                        </div>
                    </li>
                `
            },

            error: function (msg) {
                let outLi = ''
                $.each(msg.responseJSON.validate, function(key,value) {
                   outLi +=`<li>${value}</li>`
                })
                let out = `
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-danger">
                                <ul>
                                    ${outLi}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                `
                $('.errors').html('')
                $(ev.target.parentNode).find('.errors').append(out);
            }

        });
    })
