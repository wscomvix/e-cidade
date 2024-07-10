<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

Class DocumentConverter{

  /**
   * Path do bin�rio do libreoffice
   * @var string
   */
  const SOFFICE = '/usr/bin/soffice';

    /**
   * Verifica se o diret�rio existe, se tem permiss�o de escrita ou tenta cria-lo
   * @param string $dir Path do diret�rio
   * @access private
   * @return boolean
   */
  private static function checkDir( $dir )
  {
    if ( is_dir( $dir ) ){
      if ( ! is_writable( $dir ) ){
        throw new Exception( 'Diret�rio:' . $dir . ', n�o possui permiss�o de escrita!');
      }
      return true;
    }
    if ( ! mkdir( $dir, 0777, true ) ){
      throw new Exception( 'Falhou ao criar diret�rio:' . $dir );
    }
    return true;
  }

  /**
   * Verifica se o arquivo existe, e se tem permiss�o de leitura
   * @param string $file Path do diret�rio
   * @access private
   * @return boolean
   */
  private static function checkFile( $file )
  {
    if ( ! is_file( $file ) ){
      throw new Exception('Arquivo:' . $file . ', n�o encontrado!');
    }
    return true;
  }

  /**
   * Executa comando em um shell
   * @access private
   * @return string Primeira linha da execu��o
   */
  private static function execShell( $cmd ){
    $resposta = "";
    $erro = 0;
    putenv("HOME=" . ECIDADE_PATH . "tmp");
    if ( ! exec( $cmd , $resp, $erro ) ){
      if ( isset( $resp[0] ) ){
        $resposta = trim( $resp[0] );
      }
      $msg = "Falhou em executar:" . $cmd . ", " . $resposta . ", " . $erro;
      throw new Exception(  $msg  );
    }
    if ( isset( $resp[0] ) ){
      $resposta = trim( $resp[0] );
    }
    if ( $erro > 0 ){
      $msg = "O comando:" . $cmd . "foi executado, mas retornou erro:" . $erro;
      $msg .= " e " . $resposta;
      throw new Exception( $msg );
    }
    return $resposta;
  }

  /**
   * Verifica vers�o do libreofice
   * @access private
   * @return string Vers�o do libreoffice
   */
  private static function checkSoffice()
  {
    try {
      self::checkFile( self::SOFFICE );
      $cmd = self::SOFFICE . " --version | awk '{print $2}' | head -n 1";
      $resposta = self::execShell( $cmd );
    } catch (Exception $e) {
      $msg = 'Falhou em executar o soffice em :' . self::SOFFICE . ',';
      $msg .= ' Verifique se o mesmo est� instalado';
      throw new Exception( $msg );
    }
    return $resposta;
  }

  /**
   * Verifica o tipo de arquivo
   * @param string $file Path do arquivo a ser verificado
   * @access private
   * @return string Tipo de arquivo
   */
  private static function getFileType( $file )
  {
    try {
      self::checkFile( $file );
      $cmd = "file " . $file . " | cut -d ':' -f 2";
      $resposta = self::execShell( $cmd );
    } catch (Exception $e) {
      throw new Exception('Falhou ao determinar o tipo de arquivo! Retorno:' . $retorno );
    }
    return $resposta;
  }

  /**
   * Retorna o filtro a ser utilizado na convers�o de arquivos do tipo odt/sxw
   * @param string $output_type Extens�o do arquivo de sa�da
   * @access private
   * @return string Filtro a ser utilizado na convers�o
   */
  private static function getOdtSxwFilters( $output_type ){
    switch ( $output_type ) {
      case 'pdf':
        return 'writer_pdf_Export';
        break;
      case 'odt':
        return 'writer8';
        break;
      default:
        throw new Exception( 'Tipo de arquivo: ' . $file_type . ', n�o suportado!' );
        break;
    }
  }

  /**
   * Retorna o filtro a ser utilizado na convers�o de arquivos
   * @param string $input_file Arquivo a ser convertido
   * @param string $output_type Extens�o do arquivo de sa�da
   * @access private
   * @return string Filtro a ser utilizado na convers�o
   */
  private static function getFilter( $input_file, $output_type )
  {
      try {
        $file_type = self::getFileType( $input_file );
        switch ( $file_type ) {
          case 'OpenDocument Text':
            return self::getOdtSxwFilters( $output_type );
            break;
          case 'OpenOffice.org 1.x Writer document':
            return self::getOdtSxwFilters( $output_type );
            break;
          default:
            return self::getOdtSxwFilters( $output_type ); // TODO ajustar para retornar exception
            //throw new Exception( 'Tipo de arquivo: ' . $file_type . ' n�o suportado!' );
            break;
        }
      } catch (Exception $e) {
        throw $e;
      }
  }

  /**
   * Verifica se o arquivo existe, se sim tenta remove-lo
   * @param string $file_output Arquivo de sa�da
   * @access private
   * @return boolean
   */
  private static function checkOutput( $file_output ){
    if ( is_file( $file_output ) ){
      if ( ! unlink( $file_output ) ){
        throw new Exception('Arquivo:' . $file_output . ' j� existe, sem permiss�es para sobrescrever!' );
      }
    }
    return true;
  }

  /**
   * Retorna nome do arquivo e diretorio de sa�da
   * @param string $input_file Arquivo a ser convertido
   * @param string $output_file Arquivo de sa�da
   * @param string $type Extens�o do arquivo de sa�da
   * @access private
   * @return array Diret�rio e Path do arquivo de sa�da
   */
  private static function getOutputName( $input_file, $output_file = null, $type ){
    if ( $output_file == null ){
      $input_file = pathinfo( $input_file );
      $output_file = $input_file['dirname'] . DIRECTORY_SEPARATOR . $input_file['filename'] . '.' . $type;
      $output_dir = $input_file['dirname'];
    } else {
      $input_file = pathinfo( $input_file );
      $tmp_output_file = pathinfo( $output_file );
      $output_dir = $tmp_output_file['dirname'];
      if( $input_file['filename'] != $tmp_output_file['filename'] ){
        $output_file = $output_dir . DIRECTORY_SEPARATOR . $input_file['filename'] . '.' . $type;
      }
    }
    try {
      self::checkOutput( $output_file );
    } catch (Exception $e) {
      throw $e;
    }
    return array( 'dir'=> $output_dir, 'file' => $output_file );
  }

  /**
   * Executa convers�o
   * @param string $input_file Arquivo a ser convertido
   * @param string $output_file Arquivo de sa�da
   * @param string $type Extens�o do arquivo de sa�da
   * @access private
   * @return string Path do arquivo convertido
   */
  private static function convert( $input_file, $output_file = null, $type )
  {
    try {
      self::checkSoffice();
      self::checkFile( $input_file );
      $filter = self::getFilter( $input_file, $type );
      $tmp_output_file = self::getOutputName( $input_file, $output_file, $type);
      self::checkDir( $tmp_output_file['dir'] );
      self::checkDir( ECIDADE_PATH . 'tmp' );
      $cmd  = self::SOFFICE . ' --headless --nologo --convert-to '. $type .':';
      $cmd .= $filter . ' --outdir ' . $tmp_output_file['dir'] . ' ' . $input_file;
      $resposta = self::execShell( $cmd );

      if ( $output_file != null AND $output_file != $tmp_output_file['file']  ){
        self::checkOutput( $output_file );
        if ( ! rename( $tmp_output_file['file'], $output_file ) ){
          throw new Exception('Falha ao mover arquivo temporario!');
        }
      } else {
        $output_file = $tmp_output_file['file'];
      }
    } catch (Exception $e) {
      throw $e;
    }
    if ( is_writable( $input_file ) ){
      unlink( $input_file );
    }
    return $output_file;
  }

  /**
   * Converte arquivos odt/sxw/doc para pdf
   * @param string $input_file Arquivo a ser convertido
   * @param string $output_file Arquivo de sa�da
   * @access public
   * @return string Path do arquivo convertido
   */
  public static function docToPdf( $input_file, $output_file = null )
  {
    return self::convert( $input_file, $output_file, 'pdf' );
  }

  /**
   * Converte arquivos sxw para odt
   * @param string $input_file Arquivo a ser convertido
   * @param string $output_file Arquivo de sa�da
   * @access public
   * @return string Path do arquivo convertido
   */
  public static function docToOdt( $input_file, $output_file = null )
  {
    return self::convert( $input_file, $output_file, 'odt' );
  }
}
