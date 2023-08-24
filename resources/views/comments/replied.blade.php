@component('mail::message')
# Reply to comment

## {{ $answer->user->name }} replied to your comment:
@component('mail::panel')
    {{ $answer->comment->text }}
@endcomponent
## Reply text:
@component('mail::panel')
    {{ $answer->text }}
@endcomponent

@endcomponent
