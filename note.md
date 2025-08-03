1. The @extends directive in Blade is meant to extend a layout file, and it is processed before the view is rendered, which means it doesn't respect conditionals like @if. As a result, both components are being processed, regardless of the condition.Instead of using @extends for rendering components conditionally, you should use the @include directive, which is the correct way to include partial views or components in a Blade template.

2.  HasManyThrough => 
    The first argument passed to the hasManyThrough method is the name of the final model we wish to access, while the second argument is the name of the intermediate model.
    The third argument is the name of the foreign key on the intermediate model. The fourth argument is the name of the foreign key on the final model. The fifth argument is the local key, while the sixth argument is the local key of the intermediate model.

3.  using with() and whereHas()
    Using the same relationship in both with() and whereHas() is perfectly valid and even common. Here's why:

    with() is used for eager loading (to avoid N+1 query problem).

    whereHas() is used for filtering based on relationship conditions.

    They serve different purposes and can be used together without any issues as long as the relationship methods are defined correctly.