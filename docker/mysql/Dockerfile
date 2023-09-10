# Base image
FROM mysql:8.0.26

# Copy starting scripts file
COPY start.sh /root/start.sh
COPY .mylogin.cnf /root/
RUN chmod 600 /root/.mylogin.cnf

# Run necessary services
CMD ["/bin/bash", "/root/start.sh"]
