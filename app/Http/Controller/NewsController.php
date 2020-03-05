<?php

require_once('C:/laragon/www/campotv/app/Model/Connection.php');

class NewsController {

    private $connection;
    private $result = [];

    public function __construct()
    {
        $this->connection = new Connection();
        $this->result['status'] = true;
    }

    public function index()
    {
        $stmt = $this->connection->conn()->prepare("SELECT * FROM news");
        $stmt->execute();

        $this->result['message'] = 'Dados obtidos com sucesso!';
        $this->result['data'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($this->result);
    }

    public function create()
    {
        try {

            $stmt = $this->connection->prepare("INSERT INTO news (title, description, image) VALUES(:TITLE, :DESCRIPTION, :IMAGE)");

            $title = $_POST['title'];
            $description = $_POST['description'];
            $image = $_POST['image'];

            $stmt->bindParam(":TITLE", $title);
            $stmt->bindParam(":DESCRIPTION", $description);
            $stmt->bindParam(":IMAGE", $image);

            $stmt->execute();

        } catch (\Exception $e) {

            $this->result['status'] = false;
        }

        $this->result['message'] = 'Notícia adicionada com sucesso!';

        echo json_encode($this->result);
    }

    public function update()
    {
        try {

            $stmt = $this->connection->prepare("UPDATE news SET title = :TITLE, description = :DESCRIPTION, image = :IMAGE WHERE id = :ID");

            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $image = $_POST['image'];

            $stmt->bindParam(":ID", $id);
            $stmt->bindParam(":TITLE", $title);
            $stmt->bindParam(":DESCRIPTION", $description);
            $stmt->bindParam(":IMAGE", $image);

            $stmt->execute();
        } catch (\Exception $e) {

            $this->result['status'] = false;
        }

        $this->result['message'] = 'Notícia atualizada com sucesso!';

        echo json_encode($this->result);
    }

    public function delete()
    {
        try {

            $stmt = $this->connection->prepare("DELETE FROM news WHERE id = :ID");

            $this->connection->beginTransaction();

            $id = $_POST['id'];

            $stmt->bindParam(":ID", $id);

            $stmt->execute();

            $this->connection->commit();
        } catch (\Exception $e) {

            $this->result['status'] = false;
            $this->connection->rollBack();
        }

        $this->result['message'] = 'Notícia deleteda com sucesso!';

        echo json_encode($this->result);
    }

    public function export( $method, $withImage ) {

        $withImage = (integer)$_GET['image'];

        $stmt = $this->connection->prepare( "SELECT title, description, image, created_at FROM news ORDER BY created_at DESC LIMIT 3" );
        $stmt->execute();

        $data = $stmt->fetchAll( PDO::FETCH_ASSOC );

        if( $method == 'exportXml' ) {

            $xml = new DOMDocument('1.0', 'ISO-8859-1');

            $newsXml = $xml->createElement('noticias');
            $newsXml->setAttribute('criado', date('d-m-Y H:i:s'));
            $newsXml = $xml->appendChild( $newsXml );

            foreach( $data as $key => $post ) {

                $postXml = $xml->createElement("noticia" );

                foreach ( $post as $attr => $val ) {

                    if( $attr == 'title' )
                        $postXml->setAttribute('titulo', $val);
                    if( $attr == 'description' )
                        $postXml->setAttribute('texto', $val);
                    if( $attr == 'image' )
                        $postXml->setAttribute('imagem', (($withImage) ? $val : '') );
                    if( $attr == 'created_at' )
                        $postXml->setAttribute('criado', $val);
                }

                $postXml = $newsXml->appendChild( $postXml );
            }

            echo "<xmp>" . $xml->saveXML() . "</xmp>";
            exit();
        }

        if( $method == 'exportJson' ) {

            foreach( $data as $key => $post ) {

                foreach ( $post as $attr => $val ) {

                    if( $attr == 'image' && !$withImage )
                        $val = '';

                    $result[$key][$attr] = $val;
                }
            }

            echo json_encode( $result );
            exit();
        };
    }
}









