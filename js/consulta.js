
// ================================================================
//  LISTA DE TRIBUNAIS
//  u = URL do portal. {CNJ} = número formatado SEM encode.
//  Portais PJe/Seam: abre homepage (número copiado para colar)
//  Portais eSAJ / eProc: URL com parâmetro funciona diretamente
// ================================================================
var TRIBS = [
  // SUPERIORES
  {id:'stf', n:'Supremo Tribunal Federal',      ab:'STF', s:'1',
   u:'https://portal.stf.jus.br/processos/detalhe.asp?incidente={CNJ}'},
  {id:'stj', n:'Superior Tribunal de Justiça',  ab:'STJ', s:'9',
   u:'https://processo.stj.jus.br/processo/pesquisa/?tipoPesquisa=tipoPesquisaNumeroRegistro&termo={CNJ}'},
  {id:'tst', n:'Tribunal Superior do Trabalho', ab:'TST', s:'5',
   u:'https://consultaprocessual.tst.jus.br/consultaProcessual/consultaTstNumUnica.do?consulta=Consultar&numeroTst={CNJ}'},
  {id:'tse', n:'Tribunal Superior Eleitoral',   ab:'TSE', s:'2',
   u:'https://pje.tse.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'stm', n:'Superior Tribunal Militar',     ab:'STM', s:'6',
   u:'https://processos.stm.jus.br/processos/autos/{CNJ}'},

  // TRFs — 2o grau
  {id:'trf1', n:'TRF 1a Regiao — 2o Grau (AC AM AP BA DF GO MA MG MT PA PI RO RR TO)', ab:'TRF1', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=TRF1&proc={CNJ}&enviar=Pesquisar'},
  {id:'trf2', n:'TRF 2a Regiao — 2o Grau (RJ ES)', ab:'TRF2', s:'4',
   u:'https://eproc.trf2.jus.br/eproc/externo_controlador.php?acao=processo_consulta_publica&txtPesquisa={CNJ}&todasfases=S&todaspartes=S'},
  {id:'trf3', n:'TRF 3a Regiao — 2o Grau (SP MS)', ab:'TRF3', s:'4',
   u:'https://web.trf3.jus.br/consultas/Internet/ConsultaProcessual?numero_processo={CNJ}'},
  {id:'trf4', n:'TRF 4a Regiao — 2o Grau (RS SC PR)', ab:'TRF4', s:'4',
   u:'https://eproc.trf4.jus.br/eproc2trf4/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'trf5', n:'TRF 5a Regiao — 2o Grau (PE AL SE CE RN PB)', ab:'TRF5', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'trf6', n:'TRF 6a Regiao — 2o Grau (MG)', ab:'TRF6', s:'4',
   u:'https://eproc.trf6.jus.br/eproc2trf6/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},

  // JF TRF1 — 1o grau (processual.trf1 aceita proc= na URL)
  {id:'jfac', n:'JF Secao AC — 1o Grau', ab:'JFAC', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=AC&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfam', n:'JF Secao AM — 1o Grau', ab:'JFAM', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=AM&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfap', n:'JF Secao AP — 1o Grau', ab:'JFAP', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=AP&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfba', n:'JF Secao BA — 1o Grau', ab:'JFBA', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=BA&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfdf', n:'JF Secao DF — 1o Grau', ab:'JFDF', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=DF&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfgo', n:'JF Secao GO — 1o Grau', ab:'JFGO', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=GO&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfma', n:'JF Secao MA — 1o Grau', ab:'JFMA', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=MA&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfmg', n:'JF Secao MG — 1o Grau', ab:'JFMG', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=MG&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfmt', n:'JF Secao MT — 1o Grau', ab:'JFMT', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=MT&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfpa', n:'JF Secao PA — 1o Grau', ab:'JFPA', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=PA&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfpi', n:'JF Secao PI — 1o Grau', ab:'JFPI', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=PI&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfro', n:'JF Secao RO — 1o Grau', ab:'JFRO', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=RO&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfrr', n:'JF Secao RR — 1o Grau', ab:'JFRR', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=RR&proc={CNJ}&enviar=Pesquisar'},
  {id:'jfto', n:'JF Secao TO — 1o Grau', ab:'JFTO', s:'4',
   u:'https://processual.trf1.jus.br/consultaProcessual/numeroProcesso.php?secao=TO&proc={CNJ}&enviar=Pesquisar'},

  // JF TRF2
  {id:'jfrj', n:'JF Secao RJ — 1o Grau', ab:'JFRJ', s:'4',
   u:'https://eproc.trf2.jus.br/eproc/externo_controlador.php?acao=processo_consulta_publica&txtPesquisa={CNJ}&todasfases=S&todaspartes=S'},
  {id:'jfes', n:'JF Secao ES — 1o Grau', ab:'JFES', s:'4',
   u:'https://eproc.trf2.jus.br/eproc/externo_controlador.php?acao=processo_consulta_publica&txtPesquisa={CNJ}&todasfases=S&todaspartes=S'},

  // JF TRF3 — PJe (sem parâmetro GET)
  {id:'jfsp', n:'JF Secao SP — 1o Grau (PJe)', ab:'JFSP', s:'4',
   u:'https://pje1g.trf3.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'jfms', n:'JF Secao MS — 1o Grau (PJe)', ab:'JFMS', s:'4',
   u:'https://pje1g.trf3.jus.br/consultaprocessual/detalhe-processo/{RAW}'},

  // JF TRF4 — eProc (aceita txtPesquisa=)
  {id:'jfrs', n:'JF Secao RS — 1o Grau', ab:'JFRS', s:'4',
   u:'https://eproc.trf4.jus.br/eproc2trf4/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfsc', n:'JF Secao SC — 1o Grau', ab:'JFSC', s:'4',
   u:'https://eproc.trf4.jus.br/eproc2trf4/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfpr', n:'JF Secao PR — 1o Grau', ab:'JFPR', s:'4',
   u:'https://eproc.trf4.jus.br/eproc2trf4/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},

  // JF TRF5 — eProc
  {id:'jfpe', n:'JF Secao PE — 1o Grau', ab:'JFPE', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfal', n:'JF Secao AL — 1o Grau', ab:'JFAL', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfse', n:'JF Secao SE — 1o Grau', ab:'JFSE', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfce', n:'JF Secao CE — 1o Grau', ab:'JFCE', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfrn', n:'JF Secao RN — 1o Grau', ab:'JFRN', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},
  {id:'jfpb', n:'JF Secao PB — 1o Grau', ab:'JFPB', s:'4',
   u:'https://eproc.trf5.jus.br/eproc2trf5/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},

  // JF TRF6 — eProc
  {id:'jfmg2', n:'JF Subsecoes MG — 1o Grau (TRF6)', ab:'JFMG2', s:'4',
   u:'https://eproc.trf6.jus.br/eproc2trf6/controlador.php?acao=consulta_processual_pesquisa&txtPesquisa={CNJ}'},

  // TRTs — PJe (sem GET)
  {id:'trt1',  n:'TRT 1a Regiao (RJ)',        ab:'TRT1',  s:'5', u:'https://pje.trt1.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt2',  n:'TRT 2a Regiao (SP)',        ab:'TRT2',  s:'5', u:'https://pje.trt2.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt3',  n:'TRT 3a Regiao (MG)',        ab:'TRT3',  s:'5', u:'https://pje.trt3.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt4',  n:'TRT 4a Regiao (RS)',        ab:'TRT4',  s:'5', u:'https://pje.trt4.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt5',  n:'TRT 5a Regiao (BA)',        ab:'TRT5',  s:'5', u:'https://pje.trt5.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt6',  n:'TRT 6a Regiao (PE)',        ab:'TRT6',  s:'5', u:'https://pje.trt6.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt7',  n:'TRT 7a Regiao (CE)',        ab:'TRT7',  s:'5', u:'https://pje.trt7.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt8',  n:'TRT 8a Regiao (PA/AP)',     ab:'TRT8',  s:'5', u:'https://pje.trt8.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt9',  n:'TRT 9a Regiao (PR)',        ab:'TRT9',  s:'5', u:'https://pje.trt9.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt10', n:'TRT 10a Regiao (DF/TO)',    ab:'TRT10', s:'5', u:'https://pje.trt10.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt11', n:'TRT 11a Regiao (AM/RR)',    ab:'TRT11', s:'5', u:'https://pje.trt11.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt12', n:'TRT 12a Regiao (SC)',       ab:'TRT12', s:'5', u:'https://pje.trt12.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt13', n:'TRT 13a Regiao (PB)',       ab:'TRT13', s:'5', u:'https://pje.trt13.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt14', n:'TRT 14a Regiao (RO/AC)',    ab:'TRT14', s:'5', u:'https://pje.trt14.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt15', n:'TRT 15a Regiao (Campinas)', ab:'TRT15', s:'5', u:'https://pje.trt15.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt16', n:'TRT 16a Regiao (MA)',       ab:'TRT16', s:'5', u:'https://pje.trt16.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt17', n:'TRT 17a Regiao (ES)',       ab:'TRT17', s:'5', u:'https://pje.trt17.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt18', n:'TRT 18a Regiao (GO)',       ab:'TRT18', s:'5', u:'https://pje.trt18.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt19', n:'TRT 19a Regiao (AL)',       ab:'TRT19', s:'5', u:'https://pje.trt19.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt20', n:'TRT 20a Regiao (SE)',       ab:'TRT20', s:'5', u:'https://pje.trt20.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt21', n:'TRT 21a Regiao (RN)',       ab:'TRT21', s:'5', u:'https://pje.trt21.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt22', n:'TRT 22a Regiao (PI)',       ab:'TRT22', s:'5', u:'https://pje.trt22.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt23', n:'TRT 23a Regiao (MT)',       ab:'TRT23', s:'5', u:'https://pje.trt23.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'trt24', n:'TRT 24a Regiao (MS)',       ab:'TRT24', s:'5', u:'https://pje.trt24.jus.br/consultaprocessual/detalhe-processo/{RAW}'},

  // TJs — eSAJ (aceita parâmetro GET)
  {id:'tjsp', n:'Tribunal de Justica de SP 1g', ab:'TJSP1g', s:'8', u:'https://esaj.tjsp.jus.br/cpopg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjsp2g', n:'Tribunal de Justica de SP 2g', ab:'TJSP2g', s:'8', u:'https://esaj.tjsp.jus.br/cposg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjsc', n:'Tribunal de Justica de SC 1g', ab:'TJSC1g', s:'8', u:'https://esaj.tjsc.jus.br/cpopg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjsc2g', n:'Tribunal de Justica de SC 2g', ab:'TJSC2g', s:'8', u:'https://esaj.tjsc.jus.br/cposg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjba', n:'Tribunal de Justica da BA 1g', ab:'TJBA1g', s:'8', u:'https://esaj.tjba.jus.br/cpopg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjba2g', n:'Tribunal de Justica da BA 2g', ab:'TJBA2g', s:'8', u:'https://esaj.tjba.jus.br/cposg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjce', n:'Tribunal de Justica do CE 1g', ab:'TJCE1g', s:'8', u:'https://esaj.tjce.jus.br/cpopg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjce2g', n:'Tribunal de Justica do CE 2g', ab:'TJCE2g', s:'8', u:'https://esaj.tjce.jus.br/cposg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjam', n:'Tribunal de Justica do AM 1g', ab:'TJAM1g', s:'8', u:'https://consultasaj.tjam.jus.br/cpopg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjam2g', n:'Tribunal de Justica do AM 2g', ab:'TJAM2g', s:'8', u:'https://consultasaj.tjam.jus.br/cposg/search.do?cbPesquisa=NUMPROC&dadosConsulta.valorConsultaNuUnificado={CNJ}&dadosConsulta.tipoNuProcesso=UNIFICADO'},
  {id:'tjms', n:'Tribunal de Justica de MS 1g', ab:'TJMS1g', s:'8', u:'https://esaj.tjms.jus.br/cpopg5/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjms2g', n:'Tribunal de Justica de MS 2g', ab:'TJMS2g', s:'8', u:'https://esaj.tjms.jus.br/cposg5/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjal', n:'Tribunal de Justica de AL 1g', ab:'TJAL1g', s:'8', u:'https://www2.tjal.jus.br/cpopg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjal2g', n:'Tribunal de Justica de AL 2g', ab:'TJAL2g', s:'8', u:'https://www2.tjal.jus.br/cposg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjrn', n:'Tribunal de Justica do RN 1g', ab:'TJRN1g', s:'8', u:'https://esaj.tjrn.jus.br/cpopg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjrn2g', n:'Tribunal de Justica do RN 2g', ab:'TJRN2g', s:'8', u:'https://esaj.tjrn.jus.br/cposg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjac', n:'Tribunal de Justica do AC 1g', ab:'TJAC1g', s:'8', u:'https://esaj.tjac.jus.br/cpopg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  {id:'tjac2g', n:'Tribunal de Justica do AC 2g', ab:'TJAC2g', s:'8', u:'https://esaj.tjac.jus.br/cposg/show.do?processo.numero={CNJ}&cbPesquisa=NUMPROC'},
  // TJs com URL direta
  {id:'tjrj', n:'Tribunal de Justica do RJ', ab:'TJRJ', s:'8',
   u:'https://www3.tjrj.jus.br/ejuris/ImpConsultarAndamento.aspx?codbztrf={CNJ}&numProcesso={CNJ}'},
  {id:'tjmg', n:'Tribunal de Justica de MG', ab:'TJMG', s:'8',
   u:'https://www4.tjmg.jus.br/juridico/sf/proc_resultado2.jsp?listaProcessos={CNJ}'},
  {id:'tjrs', n:'Tribunal de Justica do RS', ab:'TJRS', s:'8',
   u:'https://www.tjrs.jus.br/novo/buscas-solr/?q={CNJ}&aba=jurisprudencia&tipo=consulta_por_numero'},
  {id:'tjpr', n:'Tribunal de Justica do PR', ab:'TJPR', s:'8',
   u:'https://projudi.tjpr.jus.br/projudi/processo.do?acao=consultarProcessoPublico&numeroProcesso={CNJ}'},
  {id:'tjgo', n:'Tribunal de Justica de GO', ab:'TJGO', s:'8',
   u:'https://projudi.tjgo.jus.br/BuscaProcesso?PaginaAtual=2&NumeroCNJ={CNJ}'},
  {id:'tjpe', n:'Tribunal de Justica de PE', ab:'TJPE', s:'8',
   u:'https://srv01.tjpe.jus.br/consultaprocessualunificada/processo/{CNJ}'},
  {id:'tjpa', n:'Tribunal de Justica do PA', ab:'TJPA', s:'8',
   u:'https://libra.tjpa.jus.br/LIBRA/html/consultaProcesso/consultarProcesso.seam?PN={CNJ}'},
  {id:'tjse', n:'Tribunal de Justica de SE', ab:'TJSE', s:'8',
   u:'https://www.tjse.jus.br/portal/consultas/consulta-processual?numeroProcesso={CNJ}'},
  {id:'tjdft',n:'Tribunal de Justica do DF', ab:'TJDFT',s:'8',
   u:'https://www.tjdft.jus.br/consultas/processual/consultaprocessual/pesquisar?numero={CNJ}'},
  {id:'tjto', n:'Tribunal de Justica do TO', ab:'TJTO', s:'8',
   u:'https://eproc1.tjto.jus.br/eprocV2_prod_1grau/externo_controlador.php?acao=processo_consulta_publica&num_processo={CNJ}'},
  {id:'tjes', n:'Tribunal de Justica do ES', ab:'TJES', s:'8',
   u:'https://sistemas.tjes.jus.br/ediario/index.php/component/ediario/?view=processos&consulta={CNJ}'},
  // TJs PJe (sem GET)
  {id:'tjma', n:'Tribunal de Justica do MA', ab:'TJMA', s:'8', u:'https://pje.tjma.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjmt', n:'Tribunal de Justica de MT', ab:'TJMT', s:'8', u:'https://pje.tjmt.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjpb', n:'Tribunal de Justica da PB', ab:'TJPB', s:'8', u:'https://pje.tjpb.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjpi', n:'Tribunal de Justica do PI', ab:'TJPI', s:'8', u:'https://pje.tjpi.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjrr', n:'Tribunal de Justica de RR', ab:'TJRR', s:'8', u:'https://pje.tjrr.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjro', n:'Tribunal de Justica de RO', ab:'TJRO', s:'8', u:'https://pje.tjro.jus.br/consultaprocessual/detalhe-processo/{RAW}'},
  {id:'tjap', n:'Tribunal de Justica do AP', ab:'TJAP', s:'8', u:'https://projudi.tjap.jus.br/projudi/processo.do?acao=consultarProcessoPublico&numeroProcesso={CNJ}'},
  {id:'tjspeproc1g', n:'TJSP eproc — Consulta Publica', ab:'TJSP-e1g', s:'826', u:'https://eproc-consulta.tjsp.jus.br/consulta_1g/externo_controlador.php?acao=tjsp@consulta_unificada_publica/consultar&hash=ed2215016033e517baaf4ff37bd4c428'},
  {id:'tjmgeproc1g', n:'TJMG eproc 1g', ab:'TJMG-e1g', s:'813', u:'https://eproc-consulta-publica-1g.tjmg.jus.br/eproc/externo_controlador.php?acao=processo_consulta_publica&num_processo={CNJ}'},
  {id:'tjprprojudi', n:'TJPR Projudi', ab:'TJPR-PJ', s:'816', u:'https://projudi.tjpr.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'},
  {id:'tjgoprojudi', n:'TJGO Projudi', ab:'TJGO-PJ', s:'809', u:'https://projudi.tjgo.jus.br/BuscaProcesso?NumeroProcesso={CNJ}'},
  {id:'tjmtprojudi', n:'TJMT Projudi', ab:'TJMT-PJ', s:'811', u:'https://projudi.tjmt.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'},
  {id:'tjpiprojudi', n:'TJPI Projudi', ab:'TJPI-PJ', s:'818', u:'https://projudi.tjpi.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'},
  {id:'tjroprojudi', n:'TJRO Projudi', ab:'TJRO-PJ', s:'822', u:'https://projudi.tjro.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'},
  {id:'tjrrprojudi', n:'TJRR Projudi', ab:'TJRR-PJ', s:'823', u:'https://projudi.tjrr.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'},
  {id:'tjtoprojudi', n:'TJTO Projudi', ab:'TJTO-PJ', s:'827', u:'https://projudi.tjto.jus.br/projudi/processo.do?acao=abrirConsultaPublica&numeroProcesso={CNJ}'}
];

// ================================================================
//  MAPA CNJ → TRIBUNAIS COMPETENTES
//  Chave SEMPRE = raw[13] + raw[14] + raw[15]  (3 chars)
//  Exemplo: "5003632-91.2018.4.03.6112" → raw="50036329120184036112"
//  raw[13]='4'  raw[14]='0'  raw[15]='3'  → chave='403'
// ================================================================
var CMAP = {
  // Supremos/Superiores (TT='00')
  '100':['stf'],
  '900':['stj'],
  '200':['tse'],
  '600':['stm'],
  '500':['tst'],
  // Federal
  '401':['trf1','jfac','jfam','jfap','jfba','jfdf','jfgo','jfma','jfmg','jfmt','jfpa','jfpi','jfro','jfrr','jfto'],
  '402':['trf2','jfrj','jfes'],
  '403':['trf3','jfsp','jfms'],
  '404':['trf4','jfrs','jfsc','jfpr'],
  '405':['trf5','jfpe','jfal','jfse','jfce','jfrn','jfpb'],
  '406':['trf6','jfmg2'],
  // Trabalhista
  '501':['trt1'],'502':['trt2'],'503':['trt3'],'504':['trt4'],
  '505':['trt5'],'506':['trt6'],'507':['trt7'],'508':['trt8'],
  '509':['trt9'],'510':['trt10'],'511':['trt11'],'512':['trt12'],
  '513':['trt13'],'514':['trt14'],'515':['trt15'],'516':['trt16'],
  '517':['trt17'],'518':['trt18'],'519':['trt19'],'520':['trt20'],
  '521':['trt21'],'522':['trt22'],'523':['trt23'],'524':['trt24'],
  // Estadual (Res. CNJ 65/2008: TT por UF)
  '801':['tjac','tjac2g'],'802':['tjal','tjal2g'],'803':['tjam','tjam2g'],'804':['tjap'],
  '805':['tjba','tjba2g'],'806':['tjce','tjce2g'],'807':['tjdft'],'808':['tjes'],
  '809':['tjgo','tjgoprojudi'],'810':['tjma'],'811':['tjmt','tjmtprojudi'],'812':['tjms','tjms2g'],
  '813':['tjmg','tjmgeproc1g'],'814':['tjpa'],'815':['tjpb'],'816':['tjpr','tjprprojudi'],
  '817':['tjpe'],'818':['tjpi','tjpiprojudi'],'819':['tjrj'],'820':['tjrn','tjrn2g'],
  '821':['tjrs'],'822':['tjro','tjroprojudi'],'823':['tjrr','tjrrprojudi'],'824':['tjsc','tjsc2g'],
  '825':['tjse'],'826':['tjsp','tjsp2g','tjspeproc1g'],'827':['tjto','tjtoprojudi']
};

var SNOMES = {
  '1':'STF','2':'Eleitoral','4':'Federal','5':'Trabalhista',
  '6':'Mil.Federal','7':'Mil.Estadual','8':'Estadual','9':'Superior'
};

// ================================================================
//  ESTADO
// ================================================================
var SEL = {};
TRIBS.forEach(function(t){ SEL[t.id]=true; });
var BUSY = false;
var TTIMER = null;

// ================================================================
//  GRID DE PORTAIS
// ================================================================
function buildGrid(src) {
  src = src || TRIBS;
  var g = document.getElementById('pgrid');
  g.innerHTML = '';
  src.forEach(function(t) {
    var d = document.createElement('div');
    var sel = !!SEL[t.id];
    d.className = 'pcard' + (sel ? ' sel' : '');
    d.dataset.id = t.id;
    d.innerHTML =
      '<div class="pchk">'+(sel?'&#10003;':'')+'</div>'+
      '<div class="pinfo">'+
        '<div class="pname" title="'+t.n+'">'+t.n+'</div>'+
        '<div class="pabbr">'+t.ab+' &middot; '+(SNOMES[t.s]||'?')+'</div>'+
      '</div>'+
      '<div class="psi" id="psi-'+t.id+'"></div>';
    d.addEventListener('click', function(){ toggleCard(t.id); });
    g.appendChild(d);
  });
  updCount();
}

function toggleCard(id) {
  if(BUSY) return;
  SEL[id] = !SEL[id];
  var d = document.querySelector('.pcard[data-id="'+id+'"]');
  if(d){
    d.classList.toggle('sel', !!SEL[id]);
    d.querySelector('.pchk').innerHTML = SEL[id]?'&#10003;':'';
  }
  updCount(); updBtn();
}

function selAll()  { TRIBS.forEach(function(t){ SEL[t.id]=true; }); buildGrid(); updBtn(); }
function selNone() { TRIBS.forEach(function(t){ SEL[t.id]=false; }); buildGrid(); updBtn(); }
function selSeg() {
  var seg = document.getElementById('seg-sel').value;
  TRIBS.forEach(function(t){ SEL[t.id] = seg ? (t.s===seg) : true; });
  buildGrid(seg ? TRIBS.filter(function(t){return t.s===seg;}) : null);
  updBtn();
}
function updCount() {
  var n = TRIBS.filter(function(t){return !!SEL[t.id];}).length;
  document.getElementById('pcnt').textContent = n + ' selecionados';
}

// ================================================================
//  FILTROS (selects)
// ================================================================
var segSel = document.getElementById('seg-sel');
var triSel = document.getElementById('tri-sel');

segSel.addEventListener('change', function() {
  var seg = segSel.value;
  triSel.innerHTML = '<option value="">Todos os tribunais</option>';
  var lista = seg ? TRIBS.filter(function(t){return t.s===seg;}) : TRIBS;
  lista.forEach(function(t){
    var o = document.createElement('option');
    o.value=t.id; o.textContent=t.ab+' \u2014 '+t.n;
    triSel.appendChild(o);
  });
  TRIBS.forEach(function(t){ SEL[t.id] = seg ? (t.s===seg) : true; });
  buildGrid(seg ? lista : null);
  updBtn();
});

triSel.addEventListener('change', function() {
  var v = triSel.value;
  if(!v) return;
  TRIBS.forEach(function(t){ SEL[t.id] = (t.id===v); });
  document.querySelectorAll('.pcard').forEach(function(d) {
    var s = d.dataset.id===v;
    d.classList.toggle('sel',s);
    d.querySelector('.pchk').innerHTML = s?'&#10003;':'';
  });
  updCount(); updBtn();
});

// ================================================================
//  MÁSCARA CNJ
//  Formato: NNNNNNN-DD.AAAA.J.TT.OOOO
//  Posições no raw (só dígitos, 20 chars):
//    0-6   = NNNNNNN (sequencial)
//    7-8   = DD (dígito verificador)
//    9-12  = AAAA (ano)
//    13    = J (segmento)
//    14-15 = TT (tribunal)
//    16-19 = OOOO (origem/vara)
// ================================================================
var CINP = document.getElementById('cnj-inp');

CINP.addEventListener('input', function() {
  var raw = CINP.value.replace(/\D/g,'').slice(0,20);
  var m = '';
  for(var i=0;i<raw.length;i++){
    if(i===7)  m+='-';
    if(i===9)  m+='.';
    if(i===13) m+='.';
    if(i===14) m+='.';
    if(i===16) m+='.';
    m+=raw[i];
  }
  CINP.value = m;
  var ico = document.getElementById('sico');
  CINP.classList.remove('ok','bad'); ico.classList.remove('show');
  if(raw.length===20){ CINP.classList.add('ok'); ico.innerHTML='&#9989;'; ico.classList.add('show'); }
  else if(raw.length>0){ CINP.classList.add('bad'); ico.innerHTML='&#9888;&#65039;'; ico.classList.add('show'); }
  updBtn();
});

// Cola com ou sem formatação
CINP.addEventListener('paste', function(e) {
  e.preventDefault();
  var txt = (e.clipboardData||window.clipboardData).getData('text');
  CINP.value = txt;
  CINP.dispatchEvent(new Event('input'));
});

// Enter dispara busca
CINP.addEventListener('keydown', function(e) {
  if(e.key==='Enter') document.getElementById('btn-search').click();
});

function getRaw(){ return CINP.value.replace(/\D/g,''); }
function getFmt(){
  var r=getRaw();
  if(r.length!==20) return null;
  return r.slice(0,7)+'-'+r.slice(7,9)+'.'+r.slice(9,13)+'.'+r.slice(13,14)+'.'+r.slice(14,16)+'.'+r.slice(16,20);
}

// ================================================================
//  BOTÃO
// ================================================================
function updBtn(){
  document.getElementById('btn-search').disabled = (getRaw().length!==20 || BUSY);
}

// ================================================================
//  IDENTIFICAR TRIBUNAL
//  Chave = raw[13] + raw[14] + raw[15]  (3 chars, ex: '403')
// ================================================================
function identificar(raw){
  var chave = raw[13] + raw[14] + raw[15];
  var ids = CMAP[chave];
  if(!ids) {
    // fallback: todos do segmento
    var j = raw[13];
    return TRIBS.filter(function(t){return t.s===j;}).map(function(t){return t.id;});
  }

  // TJSP (826): distinguir eproc vs eSAJ pelo número sequencial
  // Processos com sequencial >= 4.000.000 (prefixo '4') tramitam no eproc
  // Processos com sequencial < 4.000.000 tramitam no eSAJ
  if(chave === '826'){
    var seq = parseInt(raw.slice(0,7), 10);
    if(seq >= 4000000){
      // eproc: mostrar apenas portal eproc, ocultar eSAJ
      return ids.filter(function(id){ return id === 'tjspeproc1g'; });
    } else {
      // eSAJ: filtrar por OOOO (1g vs 2g)
      var esajIds = ids.filter(function(id){ return id !== 'tjspeproc1g'; });
      var oooo826 = parseInt(raw.slice(16,20), 10);
      var esaj1g = esajIds.filter(function(id){ return !id.match(/2g$/); });
      var esaj2g = esajIds.filter(function(id){ return  id.match(/2g$/); });
      if(esaj1g.length && esaj2g.length){
        return (oooo826 >= 9000) ? esaj2g : esaj1g;
      }
      return esajIds;
    }
  }

  // Filtrar 1g/2g por OOOO (código do órgão, posição 16-20)
  // OOOO < 9000 → 1ª instância (mostrar apenas 1g)
  // OOOO >= 9000 → 2ª instância (mostrar apenas 2g)
  var ids1g = ids.filter(function(id){ return !id.match(/2g$/); });
  var ids2g = ids.filter(function(id){ return  id.match(/2g$/); });
  if(ids1g.length && ids2g.length){
    var oooo = parseInt(raw.slice(16,20), 10);
    return (oooo >= 9000) ? ids2g : ids1g;
  }

  return ids;
}

// ================================================================
//  BUILD URL — NUNCA usar encodeURIComponent (tribunais esperam texto puro)
// ================================================================
function mkUrl(trib, cnj, raw){
  return trib.u.split('{CNJ}').join(cnj).split('{RAW}').join(raw);
}

// ================================================================
//  BUSCA
// ================================================================
document.getElementById('btn-search').addEventListener('click', buscar);

var _buscaToken = 0;

function buscar(){
  var cnj = getFmt();
  if(!cnj){
    toast('Informe o número CNJ completo antes de consultar.');
    return;
  }
  var raw = getRaw();

  var meuToken = ++_buscaToken;
  BUSY=true; updBtn();
  document.getElementById('spin').classList.add('on');
  document.getElementById('btxt').textContent='Identificando...';

  var pw   = document.getElementById('prog-wrap');
  var pbar = document.getElementById('prog-fill');
  var ptxt = document.getElementById('prog-txt');
  pw.style.display='block'; pbar.style.width='5%';
  ptxt.textContent='Decodificando numero CNJ...';

  document.getElementById('rlist').innerHTML='';
  document.getElementById('rsec').style.display='none';

  document.querySelectorAll('.pcard').forEach(function(d){
    d.classList.remove('hit');
    var p=document.getElementById('psi-'+d.dataset.id);
    if(p) p.textContent='';
  });

  setTimeout(function(){
    var competentes = identificar(raw);

    // Verifica se o usuário filtrou (algum tribunal está desmarcado)
    var totalSel = TRIBS.filter(function(t){return !!SEL[t.id];}).length;
    var filtrou  = totalSel < TRIBS.length;
    var exibir   = filtrou
      ? competentes.filter(function(id){return !!SEL[id];})
      : competentes;

    pbar.style.width='75%';
    ptxt.textContent='Processo identificado \u2014 '+exibir.length+' instância(s) competente(s)';

    setTimeout(function(){
      pbar.style.width='100%';

      // Cancelar se outra busca foi iniciada depois desta
      if(meuToken !== _buscaToken) return;

      // Limpar resultados anteriores antes de renderizar (evita duplicidade)
      document.getElementById('rlist').innerHTML='';

      var found=0;
      exibir.forEach(function(id){
        var t = null;
        for(var i=0;i<TRIBS.length;i++){ if(TRIBS[i].id===id){t=TRIBS[i];break;} }
        if(!t) return;
        var card = document.querySelector('.pcard[data-id="'+id+'"]');
        var psi  = document.getElementById('psi-'+id);
        if(card) card.classList.add('hit');
        if(psi)  psi.innerHTML='&#9989;';
        addResult(t, cnj, mkUrl(t,cnj,raw), raw);
        found++;
      });

      if(found>0){
        document.getElementById('rsec').style.display='block';
        ptxt.innerHTML='&#9989; Processo identificado &mdash; <strong>'+found+'</strong> tribunal'+(found>1?'is':'')+' competente'+(found>1?'s':'')+'.';
        toast('&#9989; '+found+' tribunal'+(found>1?'is':'')+' competente'+(found>1?'s':'')+' identificado'+(found>1?'s':'')+'!');
        // Scroll suave até resultados
        setTimeout(function(){ document.getElementById('rsec').scrollIntoView({behavior:'smooth',block:'start'}); },150);
      } else {
        ptxt.innerHTML='&#9888;&#65039; Nenhum tribunal competente para <strong>'+cnj+'</strong>.<br>Verifique os d\u00edgitos J (pos. 14) e TT (pos. 15-16) do n\u00famero CNJ.';
        toast('&#9888;&#65039; Tribunal n\u00e3o identificado. Verifique o n\u00famero CNJ.');
      }

      BUSY=false;
      document.getElementById('spin').classList.remove('on');
      document.getElementById('btxt').textContent='Consultar Processo';
      updBtn();
    }, 280);
  }, 320);
}

// ================================================================
//  BLOCO DE RESULTADO
// ================================================================
function addResult(t, cnj, url, raw){
  var list  = document.getElementById('rlist');
  var block = document.createElement('div');
  block.className = 'rblock';

  var j    = raw[13];
  var tt   = raw.slice(14,16);
  var ano  = raw.slice(9,13);
  var oooo = raw.slice(16,20);
  var dd   = raw.slice(7,9);
  var nseq = raw.slice(0,7);
  var inst = (t.id.indexOf('jf')===0||t.id.indexOf('trt')===0||
              (t.s==='8'&&t.id.indexOf('tj')===0)) ? '1\u00ba Grau' : '2\u00ba Grau / Superior';

  var bid  = 'body-'+t.id;
  var bcid = 'bcop-'+t.id;
  var tipid= 'tip-'+t.id;

  var tipMsg = (t.id==='tjspeproc1g')
    ? '&#128203; <strong>N\u00famero copiado!</strong> Portal eproc TJSP aberto em nova aba.<br><br>'+
        '&#9989; <strong>Como pesquisar:</strong><br>'+
        '1&#65039;&#8419; No campo <strong>N\u00ba Processo</strong>, pressione <kbd>Ctrl+V</kbd> para colar.<br>'+
        '2&#65039;&#8419; Em <strong>Inst\u00e2ncia</strong>, selecione <strong>Primeiro Grau</strong>.<br>'+
        '3&#65039;&#8419; Clique em <strong>Consultar</strong>.'
    : '\u2705 <strong>N\u00famero copiado!</strong> O portal foi aberto em nova aba.<br>Cole com <kbd>Ctrl+V</kbd> (Mac: <kbd>\u2318+V</kbd>) no campo de busca e pressione <kbd>Enter</kbd>.';

  // Monta HTML sem URL no onclick (evita corrupção por & em atributos HTML)
  block.innerHTML =
    '<div class="rhdr">'+
      '<div class="rhl">'+
        '<div class="cbadge">'+t.ab+'</div>'+
        '<div class="cname">'+t.n+'</div>'+
      '</div>'+
      '<span class="rstatus">\u2705 Competente</span>'+
    '</div>'+
    '<div class="rbody open" id="'+bid+'">'+
      '<div class="numbox">'+
        '<div>'+
          '<div class="nlbl">N\u00famero CNJ &mdash; copie e cole no portal</div>'+
          '<div class="nval">'+cnj+'</div>'+
        '</div>'+
        '<button class="bcop" id="'+bcid+'">\u{1F4CB} Copiar e abrir portal</button>'+
      '</div>'+
      '<div class="tipbox" id="'+tipid+'">'+tipMsg+'</div>'+
      '<div class="igrid">'+
        '<div class="iitem"><div class="ilbl">N\u00ba Sequencial</div><div class="ival">'+nseq+'</div></div>'+
        '<div class="iitem"><div class="ilbl">D\u00edgito Verif.</div><div class="ival">'+dd+'</div></div>'+
        '<div class="iitem"><div class="ilbl">Ano</div><div class="ival">'+ano+'</div></div>'+
        '<div class="iitem"><div class="ilbl">Segmento (J='+j+')</div><div class="ival">'+(SNOMES[j]||'?')+'</div></div>'+
        '<div class="iitem"><div class="ilbl">Tribunal (TT='+parseInt(tt,10)+')</div><div class="ival">'+t.ab+'</div></div>'+
        '<div class="iitem"><div class="ilbl">Vara / Foro</div><div class="ival">'+parseInt(oooo,10)+'</div></div>'+
        '<div class="iitem"><div class="ilbl">Inst\u00e2ncia</div><div class="ival">'+inst+'</div></div>'+
      '</div>'+
    '</div>';

  // Eventos via JS — sem risco de corrupção por caracteres especiais na URL
  var rhdr = block.querySelector('.rhdr');
  rhdr.addEventListener('click', function(){ togRes(bid, rhdr); });

  var btn = block.querySelector('#'+bcid);
  btn.addEventListener('click', function(){ copAbrir(cnj, url, t.id); });

  list.appendChild(block);
}

function togRes(bid, hdr){
  var b = document.getElementById(bid);
  if(!b) return;
  b.classList.toggle('open');
  var badge = hdr.querySelector('.rstatus');
  if(badge) badge.innerHTML = b.classList.contains('open')
    ? '&#9989; Competente' : '&#9654; Ver detalhes';
}

function clearRes(){
  document.getElementById('rlist').innerHTML='';
  document.getElementById('rsec').style.display='none';
  document.getElementById('prog-wrap').style.display='none';
  document.querySelectorAll('.pcard').forEach(function(d){
    d.classList.remove('hit');
    var p=document.getElementById('psi-'+d.dataset.id);
    if(p) p.textContent='';
  });
}

// ================================================================
//  COPIAR + ABRIR PORTAL
// ================================================================
function copAbrir(cnj, url, tid){
  if(navigator.clipboard && navigator.clipboard.writeText){
    navigator.clipboard.writeText(cnj).catch(function(){ cpFallback(cnj); });
  } else { cpFallback(cnj); }
  window.open(url,'_blank','noopener,noreferrer');
  var btn = document.getElementById('bcop-'+tid);
  var tip = document.getElementById('tip-'+tid);
  if(btn){ btn.innerHTML='&#9989; Copiado!'; btn.classList.add('done'); }
  if(tip)  tip.style.display='block';
  toast('&#128203; '+cnj+' copiado! Cole no portal com Ctrl+V.');
}

function cpFallback(txt){
  var ta=document.createElement('textarea');
  ta.value=txt; ta.style.cssText='position:fixed;opacity:0;top:0;left:0';
  document.body.appendChild(ta); ta.focus(); ta.select();
  try{document.execCommand('copy');}catch(e){}
  document.body.removeChild(ta);
}

// ================================================================
//  TOAST
// ================================================================
function toast(msg){
  var el=document.getElementById('toast');
  el.innerHTML=msg; el.classList.add('show');
  clearTimeout(TTIMER);
  TTIMER=setTimeout(function(){el.classList.remove('show');},4800);
}

// ================================================================
//  INIT
// ================================================================
buildGrid();
updBtn();

var portalToggle = document.getElementById('portal-toggle');
var portalContent = document.getElementById('portal-content');

function setPortalVisibility(open) {
  if (!portalContent || !portalToggle) return;
  portalContent.classList.toggle('collapsed', !open);
  portalToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  portalToggle.classList.toggle('open', open);
  var label = portalToggle.querySelector('.label');
  var chevron = portalToggle.querySelector('.chevron');
  if (label) label.textContent = open ? 'Ocultar portais' : 'Mostrar portais';
  if (chevron) chevron.textContent = open ? '▴' : '▾';
}

if (portalToggle && portalContent) {
  setPortalVisibility(false);
  portalToggle.addEventListener('click', function () {
    var expanded = portalContent.classList.contains('collapsed');
    setPortalVisibility(expanded);
  });
}
