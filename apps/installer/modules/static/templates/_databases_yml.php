all:
  doctrine:
    class: sfDoctrineDatabase
    param:
      dsn: 'mysql:host=<?php echo $host ?>;dbname=<?php echo $database ?>'
      username: '<?php echo $username ?>'
      password: '<?php echo $password ?>'

test:
  doctrine:
    class: sfDoctrineDatabase
    param:
      dsn: 'mysql:host=<?php echo $host ?>;dbname=<?php echo $database ?>_test'
      username: '<?php echo $username ?>'
      password: '<?php echo $password ?>'
