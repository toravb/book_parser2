<div class="pr-1 pb-1">
    <button
        type="button"
        class="btn btn-outline-danger"
        title="Удалить"
        data-action="delete-confirm"
        data-icon="warning"
        data-title="{{$title ?? 'Вы уверены что хотите удалить эту запись?'}}"
        data-text="{{$text ?? 'Это действие необратимо!'}}"
        data-route="{{$route}}"
    >
        <i class="fas fa-trash-alt"></i>
    </button>
</div>
