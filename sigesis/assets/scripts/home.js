$(document).ready(function () {
  initializeNotice();
  setInterval(nextSlide, 10000);

  const notice = [];

  $(document)
    .on("click", ".carousel-inner", () => {
      if (notice.length > 0) {
        $("#figure-modal").css(
          "background-image",
          `url(${$("#img-notice").attr("src")}`
        );
        $("#img-modal").attr("src", $("#img-notice").attr("src"));
        $(".modal-title").text($("#title-notice").text());
        $("#modal").modal("show");
      }
    })
    .css("cursor", "pointer");

  async function verificaAtualizacao() {
    const url = "./lib/notice.php";

    $("#overlay").css("display", "flex");

    try {

      let data = [];

      const checkNegadas = async () => {
        const urls = [
          "modulos/notas_negadas/loads/load_nfe.php",
          "modulos/notas_negadas/loads/load_nfce.php"
        ];

        let valida = false;
        for (const url of urls) {
          try {
            const result = await $.ajax({ type: "POST", url, dataType: "json" });

            if (result && result.dados.length > 0) {
              valida = true;
            }
          } catch (error) {
            console.error(`Erro ao carregar dados de ${url}:`, error);
          }
        }
        if (valida) {
          data.push({
            imagem: './assets/img/notas_negadas.png',
            titulo: 'Notas Negadas!',
            descricao: 'Há notas negadas na Receita, clique em "Corrigir notas" para acessa-las.',
            data_atualizacao: moment().format("DD/MM/YYYY"),
            enotas: true
          });
        }
      };

      await checkNegadas();

      const response = JSON.parse(
        await $.ajax({
          type: "POST",
          url: url,
          contentType: "application/json",
          dataType: "json",
        })
      );

      data = [
        ...data,
        ...response
          .filter((indice) => {
            return (
              indice.id_categoria == 1 &&
              moment(indice.data_atualizacao, "DD/MM/YYYY").isAfter(moment().startOf("month"))
            );
          })
          .reverse()
      ];

      if (data.length > 0) {
        if (data.length == 1) {
          $(".next, .prev").css("display", "none");
        }

        $("#img-notice").attr("src", data[0].imagem);
        $("#title-notice").text(data[0].titulo);
        $("#date-notice").text(data[0].data_atualizacao);
        $("#description-notice").text(data[0].descricao);
        $("#enotas").css("display", data[0].enotas ? "" : "none");
        $("#atualizacoes").css("display", data[0].enotas ? "none" : "");
      } else {
        $("#title-notice").text("Bem vindo!");
        $("#date-notice").text(moment().format("DD/MM/YYYY"));
        $("#description-notice").text(
          "Em breve lançaremos novas atualizações, onde poderá conferir tudo por aqui."
        );
        $("#img-notice").css("display", "none");
        $(".img-overflow").css("display", "");
        $(".next, .prev").css("display", "none");
      }

      return data;
    } catch (error) {
      alert("Error occurred. Please try again.");
      return [];
    } finally {
      $("#overlay").css("display", "none");
    }
  }

  async function initializeNotice() {
    const data = await verificaAtualizacao();
    notice.push(...data);
  }

  let currentIndex = 0;

  function showSlide(index) {
    if (notice.length === 0) return;

    if (index >= notice.length) {
      currentIndex = 0;
    } else if (index < 0) {
      currentIndex = notice.length - 1;
    } else {
      currentIndex = index;
    }

    $("#img-notice").attr("src", notice[currentIndex].imagem);
    $("#title-notice").text(notice[currentIndex].titulo);
    $("#date-notice").text(notice[currentIndex].data_atualizacao);
    $("#description-notice").text(notice[currentIndex].descricao);
    $("#enotas").css("display", notice[currentIndex].enotas ? "" : "none");
    $("#atualizacoes").css("display", notice[currentIndex].enotas ? "none" : "");
  }

  function nextSlide() {
    showSlide(currentIndex + 1);
  }

  function prevSlide() {
    showSlide(currentIndex - 1);
  }

  $(".next").on("click", function () {
    nextSlide();
  });

  $(".prev").on("click", function () {
    prevSlide();
  });
});
