<%-- Document : purchase_return_detail_view Created on : Jun 9, 2025, 11:24:12 PM Author : adamm --%>

  <%@ page contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
    <%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
      <%@ taglib uri="http://java.sun.com/jsp/jstl/fmt" prefix="fmt" %>

        <div class="detail-header">
          <h3>Detail Retur Pembelian</h3>
          <a href="${pageContext.request.contextPath}/purchase-returns/list" class="btn btn-secondary">Kembali ke
            Daftar</a>
        </div>

        <div class="card">
          <div class="card-body p-4">
            <div class="detail-info">
              <p><strong>ID Retur</strong> RET-${pReturn.id}</p>
              <p><strong>Tanggal Retur</strong>
                <fmt:formatDate value="${pReturn.returnDate}" pattern="dd MMMM yyyy, HH:mm" />
              </p>
              <p><strong>ID Pembelian Asli</strong> PO-${pReturn.originalPurchaseId}</p>
              <p><strong>Total Kredit</strong>
                <fmt:formatNumber value="${pReturn.totalCreditAmount}" type="currency" currencySymbol="Rp " />
              </p>
              <p><strong>Catatan</strong>
                <c:out value="${pReturn.notes}" default="-" />
              </p>
            </div>
            <hr>
            <div class="card-header ">
              <h4 class="mt-2">Barang yang Diretur</h4>
            </div>
            <table class="data-table">
              <thead style="background-color: #E7E7E7FF; color: #333;">
                <tr>
                  <th style="width: 5%;">No.</th>
                  <th>Nama Barang</th>
                  <th style="width: 20%; text-align:center;">Jumlah Diretur</th>
                </tr>
              </thead>
              <tbody>
                <c:forEach var="detail" items="${returnDetails}" varStatus="loop">
                  <tr>
                    <td>${loop.count}</td>
                    <td>${detail.itemName}</td>
                    <td style="text-align:center;">${detail.quantityReturned}</td>
                  </tr>
                </c:forEach>
              </tbody>
            </table>
          </div>
        </div>