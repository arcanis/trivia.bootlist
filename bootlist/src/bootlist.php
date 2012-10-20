<?php
    function getIcon( $mime ) {
        $icons = array(
            'video/.*' => 'icon-film',
            'audio/.*' => 'icon-music',
            'image/.*' => 'icon-picture',
        );

        foreach ( $icons as $regexp => $icon ) {
            if ( preg_match('#^' . $regexp . '$#', $mime ) ) {
                return $icon;
            }
        }

        return 'icon-file';
    }

    function humanFilesize( $bytes )
    {
        $s = array( 'B', 'Kb', 'MB', 'GB', 'TB', 'PB' );
        $e = floor( log( $bytes ) / log( 1024 ) );

        return sprintf( '%.2f %s', ( $bytes / pow( 1024, floor( $e ) ) ), $s[ $e ] );
    }

    define( 'BOOTLIST_ROOT', dirname( $_SERVER['SCRIPT_NAME'] ) );

    $directories = array( );
    $files = array( );

    $fileinfo = new FInfo( FILEINFO_MIME, '/usr/share/misc/magic.mgc' );
    $path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $_SERVER[ 'REQUEST_URI' ];
    if ( ( $folder = @opendir( $path ) ) === false) die( 'Unexpected error' );
    while ( ( $name = readdir( $folder ) ) !== false ) :
        $entryPath = $path . DIRECTORY_SEPARATOR . $name;
        if ( ! @stat( $entryPath ) )
            continue;
        if ( $name[ 0 ] !== '.' || $name === '..' ) :
            if ( is_dir( $entryPath ) ) :
                $directories[ ] = $name;
            else :
                $files[ $fileinfo->file( $entryPath ) ][ ] = array(
                    'name' => $name,
                    'path' => $entryPath,
                );
            endif ;
        endif ;
    endwhile ;

    sort( $directories );
    ksort( $files );
    foreach ( $files as $type => $entries )
        sort( $files[ $type ] );
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo BOOTLIST_ROOT; ?>/bootstrap/css/bootstrap.min.css" />
    </head>
    <body>
        <h1 style="margin-left: 10px;"><?php echo htmlentities( $_SERVER[ 'REQUEST_URI' ] ) ?></h1>
        <table class="table" data-provides="rowlink">
            <colgroup>
                <col width="40" />
                <col width="*" />
            </colgroup>
            <tr>
                <th></th>
                <th>Entry name</th>
            </tr>
            <?php foreach ( $directories as $directory ): ?>
                <tr>
                    <td><i class="icon-folder-close"></i></td>
                    <td><a href="<?php echo htmlentities( $directory, ENT_QUOTES ); ?>"><?php echo htmlentities( $directory ); ?></a></td>
                    <td></td>
                </tr>
            <?php endforeach ?>
            <?php foreach ( $files as $type => $entries ) : ?>
                <?php $icon = getIcon( $type ); ?>
                <?php foreach ( $entries as $entry ) : ?>
                    <tr>
                        <td><i class="<?php echo $icon; ?>"></i></td>
                        <td><a href="<?php echo htmlentities( $entry[ 'name' ], ENT_QUOTES ); ?>"><?php echo htmlentities( $entry[ 'name' ] ); ?></a></td>
                        <td><?php echo humanFilesize( filesize( $entry[ 'path' ] ) ); ?></td>
                    </tr>
                <?php endforeach ?>
            <?php endforeach ?>
        </table>
    </body>
</html>
