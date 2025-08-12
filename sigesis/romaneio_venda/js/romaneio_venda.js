import { SimpleTable } from "../../assets/plugins/SimpleTable/js/SimpleTable_class.js";

$(document).ready(function () {
  let selecionados = [];

  // DEFINE-SE A DATA ATUAL PARA DATA INICIAL E DATA FINAL
  const data = moment().format("YYYY-MM-DD");
  $("#dtini").val(data);
  $("#dtfim").val(data);

  let tabela_dinamica = new SimpleTable(
    null,
    `#dynamic-table`,
    `#pesquisa-dinamica`,
    true,
    "romaneio_venda/load/load_romaneio_venda.php",
    ".payload"
  );

  //  REALIZA BUSCA COM BASE NOS PARAMETROS (DATA INICIAL, DATA FINAL, SITUACAO DA VENDA)
  $("#buscar").on("click", function () {
    selecionados = [];
    $("#selecionados-num").text(0);
    $(".checkbox-all").prop("checked", false);

    tabela_dinamica = new SimpleTable(
      null,
      `#dynamic-table`,
      `#pesquisa-dinamica`,
      true,
      "romaneio_venda/load/load_romaneio_venda.php",
      ".payload"
    );
  });

  // AJUSTA VALORES PARA DATA INICIAL E FINAL QUANDO ALTERADOS
  $("#dtini, #dtfim").on("change", function () {
    // CASO DATA INICIAL SEJA MAIOR QUE FINAL, REALIZA-SE A INVERSAO DE VALORES DOS PARAMETROS EM QUESTAO
    if (moment($("#dtini").val()) > moment($("#dtfim").val())) {
      const auxiliar = $("#dtfim").val();
      $("#dtfim").val($("#dtini").val());
      $("#dtini").val(auxiliar);
    }
  });

  // REALIZA O CHECK DE TODOS OS CHECKBOX'S COM BASE NO VALOR DA PROPRIEDADE DE CHECKBOX-ALL (SELECIONAR TODOS)
  $(document).on("change", ".checkbox-all", function () {
    $(".checkbox-single").prop("checked", $(this).prop("checked"));
    updateSelectedValues();
  });

  $(document).on("change", ".checkbox-single", function () {
    if ($(".checkbox-single:checked").length === $(".checkbox-single").length) {
      $(".checkbox-all").prop("checked", true);
    } else {
      $(".checkbox-all").prop("checked", false);
    }
    updateSelectedValues();
  });

  const tbody = document.getElementById("dynamic-table");

  // CRIA UM OBSERVADOR PARA O COMPONENTE TBODY DO SIMPLE TABLE, PARA AVALIAR SE HOUVE ALTERACAO NOS REGISTROS
  const observer = new MutationObserver(() => {
    if (tabela_dinamica.data_array != null) {
      if ($(".checkbox-single").data("total") > 0) {
        $("#todos-num").text($(".checkbox-single").data("total"));
      }
      const data_array = tabela_dinamica.data_array;

      // VERIFICA-SE TODOS OS VALORES DE CODIGO DE VENDA DA PAGINA E MARCA-SE CHECKED PARA AS QUE ESTIVEREM EM SELECIONADOS[]
      data_array.forEach(function (row) {
        let valor = row[1];

        let checkbox = $(".checkbox-single[value='" + valor + "']");

        if (selecionados.includes(valor)) {
          checkbox.prop("checked", true);
        } else {
          checkbox.prop("checked", false);
        }
      });

      // SE TODOS OS CHECKBOX'S FOREM SELECIONADOS, CHECKBOX-ALL TAMBEM ATRIBUI-SE CHECKED, CASO CONTRARIO, NAO
      if (
        $(".checkbox-single:checked").length == $(".checkbox-single").length
      ) {
        $(".checkbox-all").prop("checked", true);
      } else {
        $(".checkbox-all").prop("checked", false);
      }
    } else {
      $("#dynamic-table tfoot").empty();
      $("#todos-num").text(0);
    }
  });

  // DISPARA-SE UM EVENTO CASO ENAH ATERACAO NA ESTRUTURA DE TBODY (ADICAO OU EXCLUSAO DE ELEMENTOS)
  observer.observe(tbody, {
    childList: true,
    subtree: false,
  });

  // FUNCAO RESPONSAVEL POR ADICIONAR OU EXCLUIR CODIGOS DE VENDA DO ARRAY SELECIONADOS
  function updateSelectedValues() {
    var novosSelecionados = $(".checkbox-single:checked")
      .map(function () {
        return this.value;
      })
      .get();

    novosSelecionados.forEach(function (valor) {
      if (!selecionados.includes(valor)) {
        selecionados.push(valor);
      }
    });

    var visiveisNaTabela = $(".checkbox-single")
      .map(function () {
        return this.value;
      })
      .get();

    selecionados = selecionados.filter(function (valor) {
      return visiveisNaTabela.includes(valor)
        ? novosSelecionados.includes(valor)
        : true;
    });
    $("#selecionados-num").text(selecionados.length);
  }

  // GERAR ROMANEIO DE VENDAS TODOS/SELECIONADOS/INDIVIDUAL
  $(document).on(
    "click",
    "#gerar-todos, #gerar-selecionados, #gerar-individual",
    function () {
      const idButton = $(this).attr("id");

      // LOGICA DE ENVIO DO PAYLOAD VIA METODO POST PARA O RELATORIO DE ROMANEIO DE VENDAS
      if (idButton == "gerar-selecionados" || idButton == "gerar-individual") {
        const form = document.createElement("form");
        form.setAttribute("method", "POST");
        form.setAttribute("action", "pdf_romaneio_vendas.php");
        form.setAttribute("target", "popup");
        form.setAttribute("style", "display: none;");

        const hiddenField = document.createElement("input");

        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", "json");
        hiddenField.setAttribute(
          "value",
          idButton == "gerar-selecionados" ? selecionados : $(this).data("id")
        );

        form.appendChild(hiddenField);

        document.body.appendChild(form);
        window.open("", "popup", "width=800,height=600");

        form.submit();
        document.body.removeChild(form);
      } else {
        const form = document.createElement("form");
        form.setAttribute("method", "POST");
        form.setAttribute("action", "pdf_romaneio_vendas.php");
        form.setAttribute("target", "popup");
        form.setAttribute("style", "display: none;");

        const hiddenFieldDtini = document.createElement("input");
        hiddenFieldDtini.setAttribute("type", "hidden");
        hiddenFieldDtini.setAttribute("name", "dtini");
        hiddenFieldDtini.setAttribute("value", $("#dtini").val());
        form.appendChild(hiddenFieldDtini);

        const hiddenFieldDtend = document.createElement("input");
        hiddenFieldDtend.setAttribute("type", "hidden");
        hiddenFieldDtend.setAttribute("name", "dtend");
        hiddenFieldDtend.setAttribute("value", $("#dtfim").val());
        form.appendChild(hiddenFieldDtend);

        const hiddenFieldPesquisa = document.createElement("input");
        hiddenFieldPesquisa.setAttribute("type", "hidden");
        hiddenFieldPesquisa.setAttribute("name", "pesquisa");
        hiddenFieldPesquisa.setAttribute(
          "value",
          $("#pesquisa-dinamica").val()
        );
        form.appendChild(hiddenFieldPesquisa);

        const hiddenFieldTpvenda = document.createElement("input");
        hiddenFieldTpvenda.setAttribute("type", "hidden");
        hiddenFieldTpvenda.setAttribute("name", "tpvenda");
        hiddenFieldTpvenda.setAttribute("value", $("#tipo-venda").val());
        form.appendChild(hiddenFieldTpvenda);

        document.body.appendChild(form);
        window.open("", "popup", "width=800,height=600");

        form.submit();
        document.body.removeChild(form);
      }
    }
  );
});
