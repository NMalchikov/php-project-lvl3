@extends('layout')
@section('main_content')
<main class="flex-grow-1">
                            <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Последняя проверка</th>
                    <th>Код ответа</th>
                </tr>
                                    <tr>
                        <td>1</td>
                        <td><a href="https://lvl3-php.herokuapp.com/urls/{id}">name</a></td>
                        <td> </td>
                        <td></td>
                    </tr>
                            </table>
            <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                
                                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">pagination.previous</span>
                    </li>
                                    <li class="page-item">
                        <a class="page-link" href="https://lvl3-php.herokuapp.com/urls?page=2" rel="next">pagination.next</a>
                    </li>
                            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
        </div>
    </nav>
        </div>
    </div>
        </main>
@endsection
