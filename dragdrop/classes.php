<?php
class _501{

    private $tipoDeRegistro;
    private $tipoDeOperacion;
    private $claveDeDocumento;
    private $numeroDePedimento;
    private $codigoDePedimento;
    private $fletes;
    private $seguros;
    private $embalajes;
    private $otrosIncrementales;
    private $otrosDeducibles;
    private $pesoBruto;
    private $bultos;
    private $transporteDeEntradaSalida;
    private $transporteArribo;
    private $transporteDeSalidaDeAduana;
    private $destinoOZona;
    private $referencia;
    private $pesos;
    private $tipoDeArchivo;
    private $paisMoneda;
    private $paisMonedaFletes;
    private $paisMonedaSeguro;
    private $paisMonedaEmbalajes;
    private $paisMonedaOtrosIncrementales;
    private $marcasNumerosBultos;
    private $grupoDeFactura;
    
    function __construct($tipoDeRegistro,$tipoDeOperacion,$claveDeDocumento,$numeroDePedimento,$codigoDePedimento,$fletes,$seguros,$embalajes,$otrosIncrementales,$otrosDeducibles,$pesoBruto,$bultos,$transporteDeEntradaSalida,$transporteArribo,$transporteDeSalidaDeAduana,$destinoOZona,$referencia,$pesos,$tipoDeArchivo,$paisMoneda,$paisMonedaFletes,$paisMonedaSeguro,$paisMonedaEmbalajes,$paisMonedaOtrosIncrementales,$marcasNumerosBultos,$grupoDeFactura){
        $this->tipoDeRegistro = $tipoDeRegistro;
        $this->tipoDeOperacion = $tipoDeOperacion;
        $this->claveDeDocumento = $claveDeDocumento;
        $this->numeroDePedimento = $numeroDePedimento;
        $this->codigoDePedimento= $codigoDePedimento;
        $this->fletes = $fletes;
        $this->seguros = $seguros;
        $this->embalajes = $embalajes;
        $this->otrosIncrementales = $otrosIncrementales;
        $this->otrosDeducibles = $otrosDeducibles;
        $this->pesoBruto = $pesoBruto;
        $this->bultos = $bultos;
        $this->transporteDeEntradaSalida =$transporteDeEntradaSalida;
        $this->transporteArribo =$transporteArribo;
        $this->transporteDeSalidaDeAduana =$transporteDeSalidaDeAduana;
        $this->destinoOZona =$destinoOZona;
        $this->referencia =$referencia;
        $this->pesos =$pesos;
        $this->tipoDeArchivo =$tipoDeArchivo;
        $this->paisMoneda =$paisMoneda;
        $this->paisMonedaFletes =$paisMonedaFletes;
        $this->paisMonedaSeguro =$paisMonedaSeguro;
        $this->paisMonedaEmbalajes =$paisMonedaEmbalajes;
        $this->paisMonedaOtrosIncrementales =$paisMonedaOtrosIncrementales;
        $this->marcasNumerosBultos =$marcasNumerosBultos;
        $this->grupoDeFactura =$grupoDeFactura;
    }

    function getTipoDeRegistro(){
        return $this->tipoDeRegistro;
    }

    function getTipoDeOperacion(){
        return $this->tipoDeOperacion;
    }

    function setTipoDeRegistro($reg){
        $this->tipoDeRegistro = $reg;
    }
}
?>
